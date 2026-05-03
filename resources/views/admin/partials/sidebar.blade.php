@php
    $current = request()->route()->getName() ?? '';
    $navItems = [
        ['section' => 'Core'],
        ['name' => 'Dashboard',  'route' => 'admin.dashboard',     'icon' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>',  'match' => 'admin.dashboard'],
        ['name' => 'Products',   'route' => 'admin.products.index', 'icon' => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>', 'match' => 'admin.products'],
        ['name' => 'Orders',     'route' => 'admin.orders.index',   'icon' => '<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>',                                    'match' => 'admin.orders'],
        ['name' => 'Pages / CMS','route' => 'admin.pages.index',    'icon' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',  'match' => 'admin.pages'],

        ['section' => 'Customers'],
        ['name' => 'Users',      'route' => 'admin.users.index',    'icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',            'match' => 'admin.users'],
        ['name' => 'Leads & Waitlist','route' => 'admin.leads.index','icon' => '<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.82a19.79 19.79 0 01-3.07-8.63A2 2 0 012 .82h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.13a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>', 'match' => 'admin.leads'],
    ];
@endphp

<aside class="admin-sidebar">
    {{-- Logo --}}
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo-text">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E8500A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            FERRO
            <span class="sidebar-logo-badge">ADMIN</span>
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
                   aria-current="{{ $isActive ? 'page' : 'false' }}">
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
            FERRO Admin &copy; {{ date('Y') }}
        </div>
    </div>
</aside>
