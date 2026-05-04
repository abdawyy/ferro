<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Lead;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Creates a storefront order from checkout payload (guest or authenticated).
 * Re-prices from the database; validates stock; upserts Lead for CRM.
 * Egypt-only checkout: shipping fee per governorate (admin), cash on delivery.
 */
class CheckoutOrderService
{
    private const TAX_RATE = '0.0500';

    /**
     * @param  array<string, mixed>  $data  Validated request payload
     */
    public function placeOrder(array $data, ?User $user): Order
    {
        $order = DB::transaction(function () use ($data, $user) {
            $itemsInput = $data['items'];
            $contact = $data['contact'];
            $shippingIn = $data['shipping'];
            $marketing = (bool) ($data['marketing_consent'] ?? false);
            $hearAbout = $data['hear_about_us'] ?? null;

            $city = ShippingCity::query()
                ->where('slug', $shippingIn['city_slug'])
                ->where('is_active', true)
                ->first();

            if (! $city) {
                throw ValidationException::withMessages([
                    'shipping.city_slug' => [__('Please choose a valid delivery location.')],
                ]);
            }

            $productIds = collect($itemsInput)->pluck('id')->unique()->all();
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lines = [];
            $subtotalStr = '0.0000';
            $scale = 4;

            foreach ($itemsInput as $row) {
                $id = (int) $row['id'];
                $qty = (int) $row['quantity'];
                /** @var Product|null $product */
                $product = $products->get($id);
                if (! $product) {
                    throw ValidationException::withMessages(['items' => [__('One or more products are no longer available.')]]);
                }
                if ($product->status !== Product::STATUS_ACTIVE) {
                    throw ValidationException::withMessages(['items' => [__(':name is not available for purchase.', ['name' => $product->getTranslation('name', 'en', false) ?: $product->name])]]);
                }
                if ($product->track_inventory && ! $product->allow_backorder && $product->stock_quantity < $qty) {
                    throw ValidationException::withMessages(['items' => [__('Not enough stock for :name.', ['name' => $product->getTranslation('name', 'en', false) ?: $product->name])]]);
                }

                $unitPrice = (string) $product->price;
                $lineNet = bcmul($unitPrice, (string) $qty, $scale);
                $subtotalStr = bcadd($subtotalStr, $lineNet, $scale);

                $lines[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'lineNet' => $lineNet,
                    'unit' => $unitPrice,
                ];
            }

            $shippingAmount = number_format((float) $city->shipping_price, 4, '.', '');
            $discountAmount = '0.0000';
            $afterDisc = bcsub($subtotalStr, $discountAmount, $scale);
            $taxAmount = bcmul($afterDisc, self::TAX_RATE, $scale);
            $total = bcadd(bcadd($afterDisc, $shippingAmount, $scale), $taxAmount, $scale);

            $orderCurrency = $lines[0]['product']->currency ?? $city->currency ?? 'EGP';

            $fullName = trim($contact['first_name'].' '.$contact['last_name']);
            $cityLabelEn = $city->getTranslation('name', 'en', false) ?: (string) $city->name;
            $cityLabelAr = $city->getTranslation('name', 'ar', false);

            $billing = [
                'first_name' => $contact['first_name'],
                'last_name' => $contact['last_name'],
                'email' => $contact['email'],
                'phone' => $contact['phone'] ?? '',
                'city' => $cityLabelEn,
                'city_slug' => $city->slug,
                'country' => $shippingIn['country'],
            ];
            $shippingAddr = [
                'name' => $fullName,
                'email' => $contact['email'],
                'address' => $shippingIn['address'],
                'city' => $cityLabelEn,
                'city_ar' => $cityLabelAr,
                'city_slug' => $city->slug,
                'country' => $shippingIn['country'],
                'phone' => $contact['phone'] ?? '',
            ];

            $lead = $this->upsertLeadFromCheckout($contact, $shippingIn, $marketing, $hearAbout);

            $order = Order::create([
                'user_id' => $user?->id,
                'lead_id' => $lead->id,
                'status' => Order::STATUS_CONFIRMED,
                'payment_status' => 'unpaid',
                'subtotal' => $subtotalStr,
                'discount_amount' => $discountAmount,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'tax_rate' => self::TAX_RATE,
                'total' => $total,
                'currency' => $orderCurrency,
                'billing_address' => $billing,
                'shipping_address' => $shippingAddr,
                'shipping_method' => 'eg_governorate:'.$city->slug,
                'payment_method' => 'cash_on_delivery',
                'payment_transaction_id' => null,
                'paid_at' => null,
                'language' => app()->getLocale(),
                'customer_notes' => $data['customer_notes'] ?? null,
                'metadata' => [
                    'source' => 'web_checkout',
                    'payment' => 'cash_on_delivery',
                    'shipping_city_slug' => $city->slug,
                    'shipping_city_fee' => $shippingAmount,
                    'hear_about_us' => $hearAbout,
                    'user_agent' => substr((string) request()->userAgent(), 0, 500),
                    'ip' => request()->ip(),
                ],
            ]);

            foreach ($lines as $line) {
                /** @var Product $product */
                $product = $line['product'];
                $qty = $line['qty'];
                $unit = $line['unit'];
                $lineNet = $line['lineNet'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_sku' => $product->sku,
                    'product_name_en' => $product->getTranslation('name', 'en', false) ?: (string) $product->name,
                    'product_name_ar' => $product->getTranslation('name', 'ar', false),
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'discount_amount' => '0.0000',
                    'tax_rate' => '0.0000',
                    'tax_amount' => '0.0000',
                    'line_total' => $lineNet,
                    'image_path' => $product->featured_image,
                    'product_options' => null,
                ]);

                if ($product->track_inventory) {
                    $product->decrement('stock_quantity', $qty);
                }
            }

            $lead->refresh();
            $mergedMarketing = $marketing || $lead->marketing_consent;
            $lead->update([
                'status' => Lead::STATUS_CONVERTED,
                'converted_at' => now(),
                'converted_order_id' => $order->id,
                'marketing_consent' => $mergedMarketing,
                'consented_at' => $lead->consented_at ?? ($mergedMarketing ? now() : null),
            ]);

            return $order->fresh(['items', 'user', 'lead']);
        });

        event(new OrderPlaced($order));

        return $order;
    }

    /**
     * @param  array<string, mixed>  $contact
     * @param  array<string, mixed>  $shippingIn
     */
    private function upsertLeadFromCheckout(array $contact, array $shippingIn, bool $marketing, ?string $hearAbout): Lead
    {
        $email = strtolower(trim($contact['email']));

        $attrs = [
            'first_name' => $contact['first_name'],
            'last_name' => $contact['last_name'],
            'phone' => $contact['phone'] ?? null,
            'preferred_language' => app()->getLocale(),
            'source' => Lead::SOURCE_CHECKOUT,
            'priority' => Lead::PRIORITY_STANDARD,
            'status' => Lead::STATUS_ENGAGED,
            'last_engaged_at' => now(),
            'ip_address' => request()->ip(),
        ];

        $custom = array_filter([
            'hear_about_us' => $hearAbout,
            'checkout_country' => $shippingIn['country'] ?? null,
            'checkout_city_slug' => $shippingIn['city_slug'] ?? null,
        ]);

        $lead = Lead::query()->where('email', $email)->first();
        if ($lead) {
            $mergeCustom = array_merge($lead->custom_attributes ?? [], $custom);
            $status = $lead->status === Lead::STATUS_CONVERTED
                ? Lead::STATUS_CONVERTED
                : Lead::STATUS_ENGAGED;
            $lead->fill(array_merge($attrs, [
                'status' => $status,
                'custom_attributes' => $mergeCustom,
                'marketing_consent' => $marketing || $lead->marketing_consent,
            ]));
            $lead->save();

            return $lead->fresh();
        }

        return Lead::create(array_merge($attrs, [
            'email' => $email,
            'marketing_consent' => $marketing,
            'gdpr_consent' => true,
            'consented_at' => now(),
            'custom_attributes' => $custom,
        ]));
    }
}
