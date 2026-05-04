<?php

namespace App\Http\Controllers;

use App\Events\LeadRegistered;
use App\Mail\Admin\QuizSubmissionAlert;
use App\Models\Lead;
use App\Models\QuizSession;
use App\Models\WaitlistEntry;
use App\Services\QuizRecommendationEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
            $pid = (int) $validated['product_id'];
            $entry = WaitlistEntry::firstOrNew([
                'product_id' => $pid,
                'email'      => $validated['email'],
            ]);
            if (! $entry->exists) {
                $entry->position = WaitlistEntry::where('product_id', $pid)->count() + 1;
            }
            $entry->fill([
                'lead_id'            => $lead->id,
                'preferred_language' => $validated['preferred_language'] ?? 'en',
            ]);
            $entry->save();
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
     * Without email: returns profile + product picks from answers.
     * With email: persists lead + structured quiz_results.
     */
    public function storeQuizLead(Request $request, QuizRecommendationEngine $engine): JsonResponse
    {
        $validated = $request->validate([
            'email'              => ['nullable', 'email', 'max:255'],
            'answers'            => ['nullable', 'array'],
            'quiz_results'       => ['nullable', 'array'],
            'preferred_language' => ['nullable', Rule::in(['en', 'ar'])],
        ]);

        $raw = $validated['answers'] ?? $validated['quiz_results'] ?? null;
        if (! is_array($raw) || count($raw) < 1) {
            return response()->json(['message' => 'Quiz answers are required.'], 422);
        }

        $normalized = [];
        foreach ($raw as $key => $value) {
            if (is_string($value) && $value !== '') {
                $normalized[(int) $key] = $value;
            }
        }
        ksort($normalized);

        $locale   = $validated['preferred_language'] ?? app()->getLocale();
        $analysis = $engine->analyzeFromUiAnswers($normalized);

        if (! $request->filled('email')) {
            return response()->json([
                'success'  => true,
                'profile'  => $analysis['profile'],
                'products' => $this->quizProductsPayload($analysis['products'], $locale),
                'tags'     => $analysis['tags'],
            ]);
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $quizResults = [
            'answers'                 => $normalized,
            'profile'                 => $analysis['profile'],
            'tags'                    => $analysis['tags'],
            'recommended_product_ids' => $analysis['product_ids'],
        ];

        $lead = Lead::updateOrCreate(
            ['email' => $validated['email']],
            [
                'source'             => Lead::SOURCE_QUIZ,
                'status'             => Lead::STATUS_ENGAGED,
                'preferred_language' => in_array($locale, ['en', 'ar'], true) ? $locale : 'en',
                'quiz_results'       => $quizResults,
                'ip_address'         => $request->ip(),
            ]
        );

        $lead->incrementEngagement(10);

        QuizSession::create([
            'session_token'             => (string) Str::uuid(),
            'user_id'                   => $request->user()?->id,
            'lead_id'                   => $lead->id,
            'answers'                   => $normalized,
            'recommended_product_ids'   => $analysis['product_ids'],
            'skin_profile'              => $analysis['profile']['label_en'] ?? null,
            'email_captured'            => true,
        ]);

        Mail::to(config('ferro.admin_email'))
            ->queue((new QuizSubmissionAlert($lead))->onQueue('notifications'));

        LeadRegistered::dispatch($lead);

        return response()->json([
            'success'  => true,
            'lead_id'  => $lead->id,
            'profile'  => $analysis['profile'],
            'products' => $this->quizProductsPayload($analysis['products'], $locale),
        ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\Product>  $products
     * @return list<array{id: int, name: string, slug: string, image: string|null, url: string}>
     */
    private function quizProductsPayload($products, string $locale): array
    {
        return $products->map(function ($product) use ($locale) {
            return [
                'id'    => $product->id,
                'name'  => $product->getTranslation('name', $locale, false) ?: $product->name,
                'slug'  => $product->slug,
                'image' => $product->featured_image ? asset($product->featured_image) : null,
                'url'   => route('products.show', $product->slug),
            ];
        })->values()->all();
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
