<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSettingController extends Controller
{
    public function edit(): View
    {
        $setting = ContactSetting::current();

        return view('admin.contact-settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'support_email' => ['required', 'email:rfc', 'max:255'],
            'email_heading_en' => ['nullable', 'string', 'max:120'],
            'email_heading_ar' => ['nullable', 'string', 'max:120'],
            'live_chat_heading_en' => ['nullable', 'string', 'max:120'],
            'live_chat_heading_ar' => ['nullable', 'string', 'max:120'],
            'live_chat_text_en' => ['nullable', 'string', 'max:500'],
            'live_chat_text_ar' => ['nullable', 'string', 'max:500'],
            'hq_heading_en' => ['nullable', 'string', 'max:120'],
            'hq_heading_ar' => ['nullable', 'string', 'max:120'],
            'hq_text_en' => ['nullable', 'string', 'max:500'],
            'hq_text_ar' => ['nullable', 'string', 'max:500'],
            'follow_heading_en' => ['nullable', 'string', 'max:120'],
            'follow_heading_ar' => ['nullable', 'string', 'max:120'],
            'social_instagram_url' => ['nullable', 'string', 'max:500'],
            'social_tiktok_url' => ['nullable', 'string', 'max:500'],
            'social_facebook_url' => ['nullable', 'string', 'max:500'],
            'social_snapchat_url' => ['nullable', 'string', 'max:500'],
            'social_whatsapp_url' => ['nullable', 'string', 'max:500'],
        ]);

        $data['show_instagram'] = $request->boolean('show_instagram');
        $data['show_tiktok'] = $request->boolean('show_tiktok');
        $data['show_facebook'] = $request->boolean('show_facebook');
        $data['show_snapchat'] = $request->boolean('show_snapchat');
        $data['show_whatsapp'] = $request->boolean('show_whatsapp');

        $setting = ContactSetting::query()->first();
        if ($setting === null) {
            ContactSetting::query()->create(array_merge(
                ContactSetting::defaultAttributes(),
                $data
            ));
        } else {
            $setting->update($data);
        }

        ContactSetting::flushMemoryCache();

        return redirect()->route('admin.contact-settings.edit')
            ->with('success', __('admin.contact_settings.saved'));
    }
}
