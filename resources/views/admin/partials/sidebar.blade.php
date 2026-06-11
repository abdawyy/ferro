@php
    $current = request()->route()->getName() ?? '';
    $navItems = [
        ['section' => __('admin.nav.section_core')],
        ['name' => __('admin.nav.dashboard'),  'route' => 'admin.dashboard',     'icon' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>',  'match' => 'admin.dashboard'],
        ['name' => __('admin.nav.products'),   'route' => 'admin.products.index', 'icon' => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>', 'match' => 'admin.products'],
        ['name' => __('admin.nav.categories'), 'route' => 'admin.product-categories.index', 'icon' => '<path d="M4 6h16M4 12h16M4 18h7"/>', 'match' => 'admin.product-categories'],
        ['name' => __('admin.nav.shop_filters'),'route' => 'admin.shop-quick-filters.index', 'icon' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>', 'match' => 'admin.shop-quick-filters'],
        ['name' => __('admin.nav.shipping_eg'),'route' => 'admin.shipping-cities.index', 'icon' => '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/>', 'match' => 'admin.shipping-cities'],
        ['name' => __('admin.nav.storefront_contact'), 'route' => 'admin.contact-settings.edit', 'icon' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>', 'match' => 'admin.contact-settings'],
        ['name' => __('admin.nav.storefront_pages'), 'route' => 'admin.pages.index', 'icon' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>', 'match' => 'admin.pages'],
        ['name' => __('admin.nav.storefront_seo'), 'route' => 'admin.storefront-seo.edit', 'icon' => '<path d="M21 12a9 9 0 10-9 9 4 4 0 01-4-4 9 9 0 109-9 4 4 0 014 4z"/><circle cx="12" cy="12" r="3"/>', 'match' => 'admin.storefront-seo'],
        ['name' => __('admin.nav.storefront_media'), 'route' => 'admin.storefront-media.edit', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>', 'match' => 'admin.storefront-media'],
        ['name' => __('admin.nav.orders'),     'route' => 'admin.orders.index',   'icon' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>',                                    'match' => 'admin.orders'],

        ['section' => __('admin.nav.section_customers')],
        ['name' => __('admin.nav.users'),      'route' => 'admin.users.index',    'icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',            'match' => 'admin.users'],
        ['name' => __('admin.nav.administrators'),'route' => 'admin.admins.index','icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>', 'match' => 'admin.admins'],
        ['name' => __('admin.nav.leads_waitlist'),'route' => 'admin.leads.index','icon' => '<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.82a19.79 19.79 0 01-3.07-8.63A2 2 0 012 .82h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.13a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>', 'match' => 'admin.leads'],
        ['name' => __('admin.nav.newsletter'),'route' => 'admin.newsletter.settings.edit','icon' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>', 'match' => 'admin.newsletter'],
        ['name' => __('admin.nav.skin_quiz'),'route' => 'admin.quiz-responses.index','icon' => '<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>', 'match' => 'admin.quiz-responses'],

        ['section' => __('admin.nav.section_help')],
        ['name' => __('admin.nav.stakeholder_manual'), 'route' => 'admin.stakeholder-manual', 'icon' => '<path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>', 'match' => 'admin.stakeholder-manual'],
    ];
@endphp

<aside class="admin-sidebar" id="admin-sidebar">
    {{-- Logo --}}
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-text" @click="navOpen = false">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E8500A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            FERRO
            <span class="sidebar-logo-badge">{{ __('admin.brand_admin') }}</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav" aria-label="Admin navigation">
        @foreach($navItems as $item)
            @if(isset($item['section']))
                <div class="sidebar-section">{{ $item['section'] }}</div>
            @else
                @php
                    $isActive = str_starts_with($current, $item['match']);
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="sidebar-link {{ $isActive ? 'active' : '' }}"
                   aria-current="{{ $isActive ? 'page' : 'false' }}"
                   @click="navOpen = false">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['name'] }}
                </a>
            @endif
        @endforeach
    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div style="font-size: 11px; color: #4B4B4B; text-align: center; letter-spacing: 0.06em;">
            {{ __('admin.footer', ['year' => date('Y')]) }}
        </div>
    </div>
</aside>
