<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSettingController extends Controller
{
    public function edit(): View
    {
        $setting = NewsletterSetting::current();

        return view('admin.newsletter.settings', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'delay_seconds' => ['required', 'integer', 'min:0', 'max:120'],
            'title_en' => ['required', 'string', 'max:200'],
            'title_ar' => ['nullable', 'string', 'max:200'],
            'message_en' => ['required', 'string', 'max:1000'],
            'message_ar' => ['nullable', 'string', 'max:1000'],
            'button_text_en' => ['required', 'string', 'max:120'],
            'button_text_ar' => ['nullable', 'string', 'max:120'],
            'success_message_en' => ['required', 'string', 'max:255'],
            'success_message_ar' => ['nullable', 'string', 'max:255'],
            'discount_percent' => ['required', 'integer', 'min:1', 'max:100'],
            'coupon_prefix' => ['required', 'string', 'max:20'],
            'coupon_valid_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $data['is_enabled'] = $request->boolean('is_enabled');

        $setting = NewsletterSetting::query()->first();
        if ($setting === null) {
            NewsletterSetting::query()->create(array_merge(
                NewsletterSetting::defaultAttributes(),
                $data
            ));
        } else {
            $setting->update($data);
        }

        NewsletterSetting::flushMemoryCache();

        return redirect()->route('admin.newsletter.settings.edit')
            ->with('success', __('admin.newsletter.settings_saved'));
    }
}
