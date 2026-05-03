<?php
// ─────────────────────────────────────────────────────────────────────────────
// FERRO — Orders & Order Items Migration
// Strict arithmetic: all monetary values stored as integers (cents) OR
// decimal(10,4) to eliminate floating-point rounding in invoice generation.
// ─────────────────────────────────────────────────────────────────────────────

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // FERRO-2025-00001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // Lead linkage — tracks pre-converted leads who place first order
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();

            // ── Status ─────────────────────────────────────────────────────
            // status: 'pending_payment' | 'confirmed' | 'processing' | 'shipped' | 'delivered' | 'cancelled' | 'refunded'
            $table->enum('status', [
                'pending_payment', 'confirmed', 'processing',
                'shipped', 'delivered', 'cancelled', 'refunded'
            ])->default('pending_payment')->index();

            $table->enum('payment_status', ['unpaid', 'paid', 'partially_refunded', 'refunded'])
                  ->default('unpaid');

            // ── Monetary values — decimal(10,4) for invoice accuracy ───────
            // RULE: All arithmetic on these must use PHP's BCMath or intl formatters.
            // Never use floats for money. Invoice generator validates: subtotal + tax + shipping = total.
            $table->decimal('subtotal', 10, 4)->default('0.0000');
            $table->decimal('discount_amount', 10, 4)->default('0.0000');
            $table->decimal('shipping_amount', 10, 4)->default('0.0000');
            $table->decimal('tax_amount', 10, 4)->default('0.0000');
            $table->decimal('tax_rate', 5, 4)->default('0.0000');   // e.g. 0.0500 = 5%
            $table->decimal('total', 10, 4)->default('0.0000');     // must == subtotal - discount + shipping + tax
            $table->string('currency', 3)->default('USD');
            $table->decimal('exchange_rate', 10, 6)->default(1.000000); // for multi-currency display

            // ── Discount / coupon ──────────────────────────────────────────
            $table->string('coupon_code')->nullable();
            $table->string('discount_type')->nullable(); // 'percent' | 'fixed'

            // ── Addresses ─────────────────────────────────────────────────
            $table->json('billing_address');
            $table->json('shipping_address');
            // {"first_name":..,"last_name":..,"address_line1":..,"city":..,"state":..,"country":..,"zip":..,"phone":..}

            // ── Shipping ───────────────────────────────────────────────────
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('carrier')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // ── Payment ────────────────────────────────────────────────────
            $table->string('payment_method')->nullable(); // 'stripe' | 'paypal' | 'cod'
            $table->string('payment_transaction_id')->nullable();
            $table->json('payment_metadata')->nullable();
            $table->timestamp('paid_at')->nullable();

            // ── Invoice ────────────────────────────────────────────────────
            $table->string('invoice_number')->nullable()->unique();  // INV-FERRO-2025-00001
            $table->string('invoice_pdf_path')->nullable();
            $table->timestamp('invoice_generated_at')->nullable();

            // ── Localization ───────────────────────────────────────────────
            $table->string('language', 2)->default('en');  // invoice rendered in this language

            // ── Internal notes ─────────────────────────────────────────────
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('metadata')->nullable(); // extensible

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'payment_status']);
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            // ── Snapshot at time of purchase (prevents data drift) ─────────
            $table->string('product_sku');
            $table->string('product_name_en');
            $table->string('product_name_ar')->nullable();

            // ── Quantities & pricing — decimal(10,4) for invoice precision ─
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 4);          // price per 1 unit
            $table->decimal('discount_amount', 10, 4)->default('0.0000'); // per line
            $table->decimal('tax_rate', 5, 4)->default('0.0000');
            $table->decimal('tax_amount', 10, 4)->default('0.0000');
            // line_total = (unit_price * quantity) - discount_amount + tax_amount
            // ENFORCED by InvoiceService::validateLineItem()
            $table->decimal('line_total', 10, 4);

            $table->string('image_path')->nullable();
            $table->json('product_options')->nullable(); // {"size":"50ml","scent":"cedar"}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
