<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

/**
 * FERRO InvoiceService
 *
 * Strict arithmetic rules:
 *  - All monetary calculations use bcmath (never float arithmetic).
 *  - Before PDF generation: validateTotals() is called and MUST pass.
 *  - If validation fails, an InvoiceArithmeticException is thrown — invoice
 *    is NOT generated until the discrepancy is resolved.
 *
 * Formula enforced:
 *   total = (subtotal - discount_amount) + shipping_amount + tax_amount
 *   line_total = (unit_price * quantity) - discount_amount  (line net, excludes line tax)
 *   order subtotal = sum of all line_total values (same as sum of line nets)
 *   order tax_amount = (subtotal - order discount) * tax_rate (order-level VAT)
 */
class InvoiceService
{
    private const SCALE = 4; // BCMath decimal scale

    /**
     * Generate and persist a PDF invoice for the given order.
     * Returns the storage path of the generated PDF.
     */
    public function generate(Order $order): string
    {
        // 1. Load all order items
        $order->load(['items', 'user', 'lead']);

        // 2. Validate arithmetic — throws on discrepancy
        $this->validateTotals($order);

        // 3. Build localized invoice view data (English / Arabic)
        $data = $this->buildInvoiceViewData($order);

        // 4. Render HTML via Blade
        $html = View::make('pdf.invoice', $data)->render();

        // 5. Generate PDF with mPDF (full Arabic/RTL + bidi support)
        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'orientation'   => 'P',
            'margin_top'    => 10,
            'margin_right'  => 10,
            'margin_bottom' => 10,
            'margin_left'   => 10,
            'autoArabic'    => true,
            'baseBasePath'  => public_path(),
        ]);
        $mpdf->autoArabic   = true;
        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);

        // 6. Store PDF
        $invoiceNumber = $order->invoice_number ?? $this->generateInvoiceNumber($order);
        $filename      = "invoice_{$invoiceNumber}.pdf";
        $path          = "invoices/{$filename}";

        Storage::disk('local')->put($path, $mpdf->Output('', 'S'));

        // 6. Persist reference to invoice
        $order->update([
            'invoice_number'       => $invoiceNumber,
            'invoice_pdf_path'     => $path,
            'invoice_generated_at' => now(),
        ]);

        return $path;
    }

    /**
     * Validate that order totals are arithmetically consistent.
     * Uses bcmath to avoid IEEE 754 floating-point errors.
     *
     * @throws \App\Exceptions\InvoiceArithmeticException
     */
    public function validateTotals(Order $order): void
    {
        $scale = self::SCALE;

        // ── 1. Validate each line item ─────────────────────────────────────
        foreach ($order->items as $item) {
            $this->validateLineItem($item);
        }

        // ── 2. Validate order-level totals ─────────────────────────────────
        // subtotal = sum of all line_totals (before tax and shipping)
        $computedSubtotal = '0.0000';
        foreach ($order->items as $item) {
            // line_total already excludes order-level tax/shipping in our schema
            $unitNet = bcsub(
                bcmul((string) $item->unit_price, (string) $item->quantity, $scale),
                (string) $item->discount_amount,
                $scale
            );
            $computedSubtotal = bcadd($computedSubtotal, $unitNet, $scale);
        }

        if (bccomp((string) $order->subtotal, $computedSubtotal, $scale) !== 0) {
            throw new \App\Exceptions\InvoiceArithmeticException(
                "Subtotal mismatch. Stored: {$order->subtotal}, Computed: {$computedSubtotal} (Order #{$order->order_number})"
            );
        }

        // total = (subtotal - discount) + shipping + tax
        $afterDiscount = bcsub((string) $order->subtotal, (string) $order->discount_amount, $scale);
        $computedTotal = bcadd(
            bcadd($afterDiscount, (string) $order->shipping_amount, $scale),
            (string) $order->tax_amount,
            $scale
        );

        if (bccomp((string) $order->total, $computedTotal, $scale) !== 0) {
            throw new \App\Exceptions\InvoiceArithmeticException(
                "Total mismatch. Stored: {$order->total}, Computed: {$computedTotal} (Order #{$order->order_number})"
            );
        }

        // Validate tax_amount = subtotal_after_discount * tax_rate
        $computedTax = bcmul(
            bcsub((string) $order->subtotal, (string) $order->discount_amount, $scale),
            (string) $order->tax_rate,
            $scale
        );

        if (bccomp((string) $order->tax_amount, $computedTax, $scale) !== 0) {
            throw new \App\Exceptions\InvoiceArithmeticException(
                "Tax amount mismatch. Stored: {$order->tax_amount}, Computed: {$computedTax} (Order #{$order->order_number})"
            );
        }
    }

    /**
     * Validate a single order line item.
     *
     * @throws \App\Exceptions\InvoiceArithmeticException
     */
    private function validateLineItem(OrderItem $item): void
    {
        $scale = self::SCALE;

        // line_total = net before tax (matches checkout / seeders); tax is rolled up at order level
        $gross     = bcmul((string) $item->unit_price, (string) $item->quantity, $scale);
        $lineNet   = bcsub($gross, (string) $item->discount_amount, $scale);

        if (bccomp((string) $item->line_total, $lineNet, $scale) !== 0) {
            throw new \App\Exceptions\InvoiceArithmeticException(
                "Line total mismatch for SKU {$item->product_sku}. " .
                "Stored: {$item->line_total}, Computed (unit×qty−discount): {$lineNet}"
            );
        }
    }

    /**
     * Build view data for the invoice PDF.
     */
    private function buildInvoiceViewData(Order $order): array
    {
        // mPDF handles Arabic bidi natively — plain strings work.
        $bi = fn (string $en, string $ar): string => $en . ' / ' . $ar;

        return [
            'order'                => $order,
            'items'                => $order->items,
            'isArabic'             => false,
            'langCode'             => 'en',
            'brandTagline'         => $bi('Forged from Iron - Polished by Luxury', 'مصقول بالفخامة - مطروق من الحديد'),
            'invoiceLabel'         => $bi('INVOICE', 'فاتورة'),
            'billingLabel'         => $bi('BILL TO', 'بيانات الفاتورة'),
            'shippingLabel'        => $bi('SHIP TO', 'عنوان الشحن'),
            'subtotalLabel'        => $bi('Subtotal', 'المجموع الفرعي'),
            'discountLabel'        => $bi('Discount', 'الخصم'),
            'shippingLabel2'       => $bi('Shipping', 'الشحن'),
            'taxLabel'             => $bi('Tax', 'الضريبة'),
            'totalLabel'           => $bi('Total', 'الإجمالي'),
            'thankYouLabel'        => $bi('Thank you for choosing FERRO', 'شكرا لاختيارك FERRO'),
            'generatedAt'          => now()->format('d M Y'),
            'orderDateFormatted'   => $order->created_at->format('d M Y'),
            'orderNumberLabel'     => $bi('Order number', 'رقم الطلب'),
            'orderDateLabel'       => $bi('Order date', 'تاريخ الطلب'),
            'paymentMethodLabel'   => $bi('Payment method', 'طريقة الدفع'),
            'paymentStatusLabel'   => $bi('Payment status', 'حالة الدفع'),
            'productLabel'         => $bi('Product', 'المنتج'),
            'unitPriceLabel'       => $bi('Unit price', 'سعر الوحدة'),
            'qtyLabel'             => $bi('Qty', 'الكمية'),
            'lineTotalLabel'       => $bi('Line total', 'إجمالي السطر'),
            'skuLabel'             => $bi('SKU', 'رمز المنتج'),
            'shippingFreeLabel'    => $bi('Free', 'مجاني'),
            'invoiceValidityNote'  => $bi(
                'This invoice is electronically generated and valid without a signature.',
                'تم إنشاء هذه الفاتورة إلكترونيا وهي صالحة بدون توقيع.'
            ),
            'paymentMethodDisplay' => $bi(
                $this->paymentMethodLabel($order->payment_method, false),
                $this->paymentMethodLabel($order->payment_method, true)
            ),
            'paymentStatusDisplay' => $bi(
                $this->paymentStatusLabel($order->payment_status, false),
                $this->paymentStatusLabel($order->payment_status, true)
            ),
        ];
    }

    private function paymentMethodLabel(?string $method, bool $isArabic = false): string
    {
        $m = strtolower((string) $method);

        if ($isArabic) {
            return match ($m) {
                'cash_on_delivery', 'cod' => 'الدفع عند الاستلام',
                'visa', 'card', 'credit_card' => 'بطاقة',
                'fawry' => 'فوري',
                'apple_pay' => 'Apple Pay',
                'mada' => 'مدى',
                default => $method ? $method : 'غير متوفر',
            };
        }

        return match ($m) {
            'cash_on_delivery', 'cod' => 'Cash on delivery',
            'visa', 'card', 'credit_card' => 'Card',
            'fawry' => 'Fawry',
            'apple_pay' => 'Apple Pay',
            'mada' => 'Mada',
            default => $method ? ucwords(str_replace('_', ' ', $method)) : 'N/A',
        };
    }

    private function paymentStatusLabel(?string $status, bool $isArabic = false): string
    {
        $s = strtolower((string) $status);

        if ($isArabic) {
            return match ($s) {
                'paid' => 'مدفوع',
                'unpaid' => 'غير مدفوع',
                'pending' => 'قيد الانتظار',
                'refunded' => 'مسترد',
                'partially_refunded' => 'استرداد جزئي',
                default => $status ? $status : 'غير متوفر',
            };
        }

        return match ($s) {
            'paid' => 'Paid',
            'unpaid' => 'Unpaid',
            'pending' => 'Pending',
            'refunded' => 'Refunded',
            'partially_refunded' => 'Partially refunded',
            default => $status ? ucwords(str_replace('_', ' ', $status)) : 'N/A',
        };
    }

    private function generateInvoiceNumber(Order $order): string
    {
        $year     = now()->format('Y');
        $sequence = str_pad($order->id, 6, '0', STR_PAD_LEFT);
        return "INV-FERRO-{$year}-{$sequence}";
    }
}
