<!DOCTYPE html>
{{--
    FERRO Email Layout
    Supports: RTL/LTR, branded header/footer, responsive tables
    Variables: $locale, $isRtl
--}}
<html lang="{{ $locale ?? 'en' }}" dir="{{ ($isRtl ?? false) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('email_title', 'FERRO')</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #0F0F0F; color: #F5F2EE; line-height: 1.6; -webkit-font-smoothing: antialiased; direction: {{ ($isRtl ?? false) ? 'rtl' : 'ltr' }}; }
        a { color: #E8500A; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* Layout */
        .email-wrapper   { width: 100%; background-color: #0F0F0F; padding: 40px 16px; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #1A1A1A; border: 1px solid #2A2A2A; border-radius: 2px; overflow: hidden; }

        /* Header */
        .email-header         { padding: 0; border-bottom: 1px solid #2A2A2A; background-color: #0A0A0A; }
        .email-header-banner  { display: block; width: 100%; max-width: 600px; height: auto; border: 0; }
        .header-orange-bar    { height: 2px; background: linear-gradient(to right, #E8500A, #FF6B2B, transparent); margin-top: 0; }

        /* Body */
        .email-body        { padding: 40px; }
        .email-heading     { font-size: 28px; font-weight: 600; color: #FFFFFF; line-height: 1.2; margin-bottom: 8px; }
        .email-subheading  { font-size: 14px; color: #B0B0B0; margin-bottom: 28px; }
        .email-text        { font-size: 14px; color: #B0B0B0; line-height: 1.7; margin-bottom: 16px; }
        .email-text strong { color: #F5F2EE; }

        /* Divider */
        .email-divider     { border: none; border-top: 1px solid #2A2A2A; margin: 24px 0; }

        /* Button */
        .email-btn         { display: inline-block; padding: 14px 32px; background-color: #E8500A; color: #FFFFFF !important; font-size: 13px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none !important; border-radius: 2px; }
        .email-btn:hover   { background-color: #C43E06; }
        .email-btn-center  { text-align: center; margin: 28px 0; }

        /* Order table */
        .order-table       { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .order-table th    { padding: 10px 14px; background-color: #0A0A0A; color: #6B6B6B; font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; border-bottom: 1px solid #2A2A2A; text-align: {{ ($isRtl ?? false) ? 'right' : 'left' }}; }
        .order-table td    { padding: 12px 14px; border-bottom: 1px solid #2A2A2A; font-size: 13px; color: #F5F2EE; vertical-align: top; text-align: {{ ($isRtl ?? false) ? 'right' : 'left' }}; }
        .order-table tr:last-child td { border-bottom: none; }
        .total-row td      { border-top: 1px solid #2A2A2A; font-weight: 600; color: #FFFFFF; background-color: #0A0A0A; }
        .total-row .grand-total { color: #E8500A; font-size: 16px; }

        /* Status badge */
        .status-badge      { display: inline-block; padding: 4px 12px; font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; border-radius: 2px; }
        .badge-success     { background-color: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.3); }
        .badge-warning     { background-color: rgba(234,179,8,0.1);  color: #eab308; border: 1px solid rgba(234,179,8,0.3); }
        .badge-info        { background-color: rgba(232,80,10,0.1);  color: #E8500A; border: 1px solid rgba(232,80,10,0.3); }
        .badge-danger      { background-color: rgba(239,68,68,0.1);  color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }

        /* Info box */
        .info-box          { background-color: #0A0A0A; border: 1px solid #2A2A2A; border-{{ ($isRtl ?? false) ? 'right' : 'left' }}: 3px solid #E8500A; padding: 16px 20px; margin: 20px 0; border-radius: 2px; }
        .info-box p        { font-size: 13px; color: #B0B0B0; margin: 0; }
        .info-box strong   { color: #F5F2EE; }

        /* Footer */
        .email-footer      { padding: 24px 40px; border-top: 1px solid #2A2A2A; background-color: #0A0A0A; text-align: center; }
        .email-footer p    { font-size: 11px; color: #6B6B6B; margin-bottom: 6px; }
        .email-footer a    { color: #6B6B6B; font-size: 11px; }
        .email-footer a:hover { color: #E8500A; }
        .footer-logo       { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px; }
        .footer-wordmark   { font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #F5F2EE; }

        /* Admin-specific */
        .admin-alert-header { background: linear-gradient(135deg, #1A0A00, #1A1A1A); border-bottom: 2px solid #E8500A; }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-body, .email-footer { padding: 24px 20px !important; }
            .email-heading { font-size: 22px !important; }
        }
    </style>
    @stack('email_head')
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">

        {{-- ── Header ─────────────────────────────────────────────────── --}}
        <div class="email-header @yield('header_class')">
            <a href="{{ url('/') }}" style="display:block;text-decoration:none;padding:28px 24px 24px;background-color:#0A0A0A;">
                <table role="presentation" cellspacing="0" cellpadding="0" align="center" style="margin:0 auto;">
                    <tr>
                        <td style="vertical-align:middle;padding-right:12px;">
                            <span style="font-size:32px;font-weight:700;color:#E8500A;line-height:1;font-family:Georgia,'Times New Roman',serif;">F</span>
                        </td>
                        <td style="vertical-align:middle;">
                            <span style="font-size:18px;font-weight:700;letter-spacing:0.35em;color:#F5F2EE;font-family:Georgia,'Times New Roman',serif;">FERRO</span>
                        </td>
                    </tr>
                </table>
            </a>
            <div class="header-orange-bar"></div>
        </div>

        {{-- ── Body ───────────────────────────────────────────────────── --}}
        <div class="email-body">
            @yield('email_body')
        </div>

        {{-- ── Footer ─────────────────────────────────────────────────── --}}
        <div class="email-footer">
            <div class="footer-logo">
                <svg style="width:20px;height:20px;" viewBox="0 0 32 32" fill="#E8500A">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                </svg>
                <span class="footer-wordmark">FERRO</span>
            </div>
            <p>{{ $isRtl ?? false ? 'مصنوع من الحديد، مصقول بالرفاهية.' : 'Forged from Iron. Polished by Luxury.' }}</p>
            <p style="margin-top:8px;">
                <a href="{{ url('/') }}">{{ url('/') }}</a>
                &nbsp;·&nbsp;
                <a href="mailto:{{ $contactSetting->support_email }}">{{ $contactSetting->support_email }}</a>
            </p>
            <p style="margin-top:12px;">
                © {{ date('Y') }} FERRO.
                {{ $isRtl ?? false ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
            </p>
            @yield('email_footer_extra')
        </div>

    </div>
</div>
</body>
</html>
