<?php

namespace App\Http\Controllers;

use App\Events\LeadRegistered;
use App\Models\Lead;
use App\Models\Product;
use App\Models\WaitlistEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Handles all Lead capture endpoints:
 *  - Waitlist signup (product-specific or general)
 *  - Newsletter opt-in
 *  - Quiz lead capture
 *  - Abandoned cart tracking
 */
class LeadController extends Controller
{
    /**
     * POST /waitlist
     * Capture a waitlist signup lead.
     */
    public function storeWaitlist(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email'              => ['required', 'email', 'max:255'],
            'first_name'         => ['nullable', 'string', 'max:100'],
            'last_name'          => ['nullable', 'string', 'max:100'],
            'product_id'         => ['nullable', 'exists:products,id'],
            'preferred_language' => ['nullable', Rule::in(['en', 'ar'])],
            'marketing_consent'  => ['nullable', 'boolean'],
            'gdpr_consent'       => ['nullable', 'boolean'],
        ]);

        // Upsert lead — idempotent by email
        $lead = Lead::updateOrCreate(
            ['email' => $validated['email']],
            [
                'first_name'         => $validated['first_name'] ?? null,
                'last_name'          => $validated['last_name']  ?? null,
                'source'             => Lead::SOURCE_WAITLIST,
                'status'             => Lead::STATUS_NEW,
                'preferred_language' => $validated['preferred_language'] ?? 'en',
                'on_waitlist'        => true,
                'waitlist_product_id'=> $validated['product_id'] ?? null,
                'marketing_consent'  => $validated['marketing_consent'] ?? false,
                'gdpr_consent'       => $validated['gdpr_consent'] ?? false,
                'consented_at'       => now(),
                'ip_address'         => $request->ip(),
                'utm_data'           => array_filter([
                    'utm_source'   => $request->utm_source,
                    'utm_medium'   => $request->utm_medium,
                    'utm_campaign' => $request->utm_campaign,
                    'utm_content'  => $request->utm_content,
                    'referrer'     => $request->header('Referer'),
                ]),
            ]
        );

        // Product-specific waitlist entry
        if (isset($validated['product_id'])) {
            WaitlistEntry::firstOrCreate([
                'product_id' => $validated['product_id'],
                'email'      => $validated['email'],
            ], [
                'lead_id'             => $lead->id,
                'preferred_language'  => $validated['preferred_language'] ?? 'en',
                'position'            => WaitlistEntry::where('product_id', $validated['product_id'])->count() + 1,
            ]);
        }

        // Dispatch event — triggers welcome email + admin alert if VIP
        LeadRegistered::dispatch($lead);

        $message = $lead->preferred_language === 'ar'
            ? 'تم تسجيلك بنجاح! سنبلغك عند الإطلاق.'
            : "You're on the list! We'll notify you at launch.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'lead_id' => $lead->id,
            ]);
        }

        return back()->with('waitlist_success', $message);
    }

    /**
     * POST /quiz/capture
     * Capture lead email from skincare quiz (Advanced Feature #2).
     */
    public function storeQuizLead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'              => ['required', 'email'],
            'quiz_results'       => ['required', 'array'],
            'skin_profile'       => ['nullable', 'string', 'max:100'],
            'preferred_language' => ['nullable', Rule::in(['en', 'ar'])],
        ]);

        $lead = Lead::updateOrCreate(
            ['email' => $validated['email']],
            [
                'source'             => Lead::SOURCE_QUIZ,
                'status'             => Lead::STATUS_ENGAGED,
                'preferred_language' => $validated['preferred_language'] ?? 'en',
                'quiz_results'       => $validated['quiz_results'],
                'ip_address'         => $request->ip(),
            ]
        );

        // Elevate engagement score for quiz completion
        $lead->incrementEngagement(10);

        LeadRegistered::dispatch($lead);

        return response()->json(['success' => true, 'lead_id' => $lead->id]);
    }

    /**
     * POST /cart/abandon
     * Track abandoned cart data for recovery emails.
     * Called via JavaScript beacon when user exits checkout.
     */
    public function trackAbandonedCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'       => ['required', 'email'],
            'cart_items'  => ['required', 'array'],
            'cart_value'  => ['required', 'numeric', 'min:0'],
        ]);

        $lead = Lead::updateOrCreate(
            ['email' => $validated['email']],
            [
                'source'                  => Lead::SOURCE_ABANDONED_CART,
                'abandoned_cart_items'    => $validated['cart_items'],
                'abandoned_cart_value'    => $validated['cart_value'],
                'cart_abandoned_at'       => now(),
            ]
        );

        // Schedule recovery email sequence via queued job
        dispatch(new \App\Jobs\SendAbandonedCartSequence($lead))
            ->delay(now()->addHour());

        return response()->json(['success' => true]);
    }
}
