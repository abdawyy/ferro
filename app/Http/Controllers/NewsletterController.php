<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function __construct(private readonly NewsletterService $newsletter) {}

    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'preferred_language' => ['nullable', 'in:en,ar'],
        ]);

        $locale = $validated['preferred_language'] ?? app()->getLocale();
        $result = $this->newsletter->subscribe(
            $validated['email'],
            $locale,
            $request->ip()
        );

        $settings = \App\Models\NewsletterSetting::current();
        $message = $settings->successMessage($locale);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'already_subscribed' => $result['already_active'],
            ]);
        }

        return back()->with('newsletter_success', $message);
    }

    public function unsubscribe(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
        ]);

        $valid = $this->newsletter->verifyUnsubscribeToken($validated['email'], $validated['token']);
        $locale = app()->getLocale();
        $isAr = $locale === 'ar';

        if (! $valid) {
            return view('newsletter.unsubscribe', [
                'success' => false,
                'message' => $isAr
                    ? 'رابط إلغاء الاشتراك غير صالح.'
                    : 'This unsubscribe link is invalid.',
            ]);
        }

        $this->newsletter->unsubscribe($validated['email']);

        return view('newsletter.unsubscribe', [
            'success' => true,
            'message' => $isAr
                ? 'تم إلغاء اشتراكك في النشرة الإخبارية.'
                : 'You have been unsubscribed from our newsletter.',
        ]);
    }
}
