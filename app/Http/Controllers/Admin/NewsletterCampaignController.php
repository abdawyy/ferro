<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterCampaignController extends Controller
{
    public function __construct(private readonly NewsletterService $newsletter) {}

    public function index(): View
    {
        $campaigns = NewsletterCampaign::query()
            ->with(['product', 'creator'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.newsletter.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $products = Product::query()
            ->where('status', Product::STATUS_ACTIVE)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug']);

        $subscribers = NewsletterSubscriber::active()
            ->orderBy('email')
            ->get(['id', 'email']);

        return view('admin.newsletter.campaigns.create', compact('products', 'subscribers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_en' => ['required', 'string', 'max:200'],
            'subject_ar' => ['nullable', 'string', 'max:200'],
            'body_en' => ['required', 'string', 'max:5000'],
            'body_ar' => ['nullable', 'string', 'max:5000'],
            'product_id' => ['nullable', 'exists:products,id'],
            'send_to' => ['required', 'in:all,selected'],
            'subscriber_ids' => ['nullable', 'array'],
            'subscriber_ids.*' => ['integer', 'exists:newsletter_subscribers,id'],
        ]);

        if ($data['send_to'] === NewsletterCampaign::SEND_TO_SELECTED
            && empty($data['subscriber_ids'])) {
            return back()->withInput()->with('error', __('admin.newsletter.select_subscribers'));
        }

        $campaign = NewsletterCampaign::create([
            'subject_en' => $data['subject_en'],
            'subject_ar' => $data['subject_ar'] ?? null,
            'body_en' => $data['body_en'],
            'body_ar' => $data['body_ar'] ?? null,
            'product_id' => $data['product_id'] ?? null,
            'send_to' => $data['send_to'],
            'status' => NewsletterCampaign::STATUS_DRAFT,
            'created_by' => $request->user()->id,
        ]);

        if ($data['send_to'] === NewsletterCampaign::SEND_TO_SELECTED) {
            $campaign->subscribers()->sync($data['subscriber_ids'] ?? []);
        }

        return redirect()->route('admin.newsletter.campaigns.show', $campaign)
            ->with('success', __('admin.newsletter.campaign_created'));
    }

    public function show(NewsletterCampaign $campaign): View
    {
        $campaign->load(['product', 'creator', 'subscribers']);

        return view('admin.newsletter.campaigns.show', compact('campaign'));
    }

    public function send(NewsletterCampaign $campaign): RedirectResponse
    {
        if ($campaign->isSent()) {
            return back()->with('error', __('admin.newsletter.campaign_already_sent'));
        }

        $sent = $this->newsletter->sendCampaign($campaign);

        return redirect()->route('admin.newsletter.campaigns.show', $campaign)
            ->with('success', __('admin.newsletter.campaign_sent', ['count' => $sent]));
    }
}
