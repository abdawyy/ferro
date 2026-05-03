<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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
 *   line_total = (unit_price * quantity) - line_discount + line_tax
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

        // 3. Determine rendering language
        $locale = $order->language ?? 'en';
        app()->setLocale($locale);

        // 4. Prepare invoice data
        $data = $this->buildInvoiceData($order, $locale);

        // 5. Render PDF via DomPDF (uses Blade view)
        $pdf = Pdf::loadView('pdf.invoice', $data)
                  ->setPaper('a4', 'portrait')
                  ->setOption('dpi', 150)
                  ->setOption('defaultFont', $locale === 'ar' ? 'DejaVu Sans' : 'DejaVu Sans');

        // 6. Store PDF
        $invoiceNumber = $order->invoice_number ?? $this->generateInvoiceNumber($order);
        $filename      = "invoice_{$invoiceNumber}.pdf";
        $path          = "invoices/{$filename}";

        Storage::disk('local')->put($path, $pdf->output());

        // 7. Persist reference to invoice
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

        // line_total = (unit_price * qty) - discount + tax
        $gross    = bcmul((string) $item->unit_price, (string) $item->quantity, $scale);
        $afterDisc= bcsub($gross, (string) $item->discount_amount, $scale);
        $computed = bcadd($afterDisc, (string) $item->tax_amount, $scale);

        if (bccomp((string) $item->line_total, $computed, $scale) !== 0) {
            throw new \App\Exceptions\InvoiceArithmeticException(
                "Line total mismatch for SKU {$item->product_sku}. " .
                "Stored: {$item->line_total}, Computed: {$computed}"
            );
        }
    }

    /**
     * Build view data for the invoice Blade template.
     */
    private function buildInvoiceData(Order $order, string $locale): array
    {
        $isRtl = $locale === 'ar';

        return [
            'order'         => $order,
            'items'         => $order->items,
            'locale'        => $locale,
            'isRtl'         => $isRtl,
            'currencySymbol'=> $order->currency === 'USD' ? '$' : ($order->currency === 'AED' ? 'AED' : $order->currency),
            'brandName'     => 'FERRO',
            'brandTagline'  => $isRtl ? 'مصنوع من الحديد — مصقول بالرفاهية' : 'Forged from Iron — Polished by Luxury',
            'invoiceLabel'  => $isRtl ? 'فاتورة' : 'INVOICE',
            'billingLabel'  => $isRtl ? 'عنوان الفواتير' : 'BILL TO',
            'shippingLabel' => $isRtl ? 'عنوان الشحن' : 'SHIP TO',
            'subtotalLabel' => $isRtl ? 'المجموع الفرعي' : 'Subtotal',
            'discountLabel' => $isRtl ? 'الخصم' : 'Discount',
            'shippingLabel2'=> $isRtl ? 'الشحن' : 'Shipping',
            'taxLabel'      => $isRtl ? 'الضريبة' : 'Tax',
            'totalLabel'    => $isRtl ? 'الإجمالي' : 'Total',
            'thankYouLabel' => $isRtl
                ? 'شكراً لثقتك في فيرو'
                : 'Thank you for choosing FERRO',
            'generatedAt'   => now()->format('d M Y'),
        ];
    }

    private function generateInvoiceNumber(Order $order): string
    {
        $year     = now()->format('Y');
        $sequence = str_pad($order->id, 6, '0', STR_PAD_LEFT);
        return "INV-FERRO-{$year}-{$sequence}";
    }
}
