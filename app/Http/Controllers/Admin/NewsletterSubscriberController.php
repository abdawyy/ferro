<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Response;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    public function index(): View
    {
        $subscribers = NewsletterSubscriber::query()
            ->latest('subscribed_at')
            ->paginate(25)
            ->withQueryString();

        $stats = [
            'active' => NewsletterSubscriber::active()->count(),
            'total' => NewsletterSubscriber::count(),
        ];

        return view('admin.newsletter.subscribers.index', compact('subscribers', 'stats'));
    }

    public function export(): Response
    {
        $rows = NewsletterSubscriber::query()->orderBy('subscribed_at')->get();

        $csv = "email,status,coupon_code,discount_percent,subscribed_at,unsubscribed_at\n";
        foreach ($rows as $row) {
            $csv .= implode(',', [
                '"'.str_replace('"', '""', $row->email).'"',
                $row->isActive() ? 'active' : 'unsubscribed',
                $row->coupon_code,
                $row->discount_percent,
                $row->subscribed_at?->toDateTimeString() ?? '',
                $row->unsubscribed_at?->toDateTimeString() ?? '',
            ])."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="newsletter-subscribers-'.now()->format('Y-m-d').'.csv"',
        ]);
    }
}
