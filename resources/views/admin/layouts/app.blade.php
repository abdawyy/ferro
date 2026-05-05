<!DOCTYPE html>
@php($adminLocale = app()->getLocale())
<html lang="{{ str_replace('_', '-', $adminLocale) }}" dir="{{ $adminLocale === 'ar' ? 'rtl' : 'ltr' }}" class="{{ $adminLocale === 'ar' ? 'admin-locale-ar' : 'admin-locale-en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ferro-app-url" content="{{ url('/') }}">
    <meta name="ferro-cart-add-url" content="{{ route('api.cart.add') }}">
    <title>@yield('title', __('admin.dashboard.title')) — FERRO Admin</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.vite-assets')

    <style>
        :root {
            --admin-bg:       #0D0D0D;
            --admin-surface:  #141414;
            --admin-border:   #242424;
            --admin-text:     #E5E5E5;
            --admin-muted:    #737373;
            --admin-orange:   #E8500A;
            --admin-orange-h: #FF6B2B;
            --admin-green:    #22C55E;
            --admin-yellow:   #EAB308;
            --admin-red:      #EF4444;
            --admin-blue:     #3B82F6;
            --sidebar-w:      260px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
            font-size: 14px;
            line-height: 1.5;
        }

        .admin-locale-ar body {
            font-family: 'Noto Sans Arabic', 'Inter', sans-serif;
        }

        /* RTL layout */
        html[dir="rtl"] .admin-sidebar {
            left: auto;
            right: 0;
            border-right: none;
            border-left: 1px solid var(--admin-border);
        }
        html[dir="rtl"] .admin-main {
            margin-left: 0;
            margin-right: var(--sidebar-w);
        }
        html[dir="rtl"] .sidebar-link.active {
            border-left: none;
            border-right: 2px solid var(--admin-orange);
            padding-left: 16px;
            padding-right: 14px;
        }
        html[dir="rtl"] .sidebar-badge {
            margin-left: 0;
            margin-right: auto;
        }
        @media (max-width: 768px) {
            html[dir="rtl"] .admin-sidebar {
                transform: translateX(100%);
            }
            html[dir="rtl"] .admin-wrap.nav-open .admin-sidebar {
                transform: translateX(0);
            }
            html[dir="ltr"] .admin-sidebar {
                transform: translateX(-100%);
            }
            html[dir="ltr"] .admin-wrap.nav-open .admin-sidebar {
                transform: translateX(0);
            }
        }

        /* ── Layout ── */
        .admin-wrap        { display: flex; min-height: 100vh; }
        .admin-sidebar     { width: var(--sidebar-w); flex-shrink: 0; background: var(--admin-surface); border-right: 1px solid var(--admin-border); display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; overflow-y: auto; z-index: 50; transition: transform 0.22s ease; }
        .admin-main        { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }
        .admin-topbar      { background: var(--admin-surface); border-bottom: 1px solid var(--admin-border); padding: 0 24px; min-height: 56px; display: flex; align-items: center; justify-content: space-between; gap: 12px; position: sticky; top: 0; z-index: 40; flex-wrap: wrap; }
        .admin-topbar-start{ display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; }
        .admin-content     { padding: 28px 28px; flex: 1; min-width: 0; }
        .admin-nav-toggle  { display: none; align-items: center; justify-content: center; width: 42px; height: 42px; padding: 0; border: 1px solid var(--admin-border); border-radius: 4px; background: #1A1A1A; color: var(--admin-text); cursor: pointer; flex-shrink: 0; transition: background 0.15s, border-color 0.15s; }
        .admin-nav-toggle:hover { background: rgba(255,255,255,0.06); border-color: #404040; }
        .admin-nav-toggle svg { width: 22px; height: 22px; }
        .admin-mobile-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 45; }
        .admin-wrap.nav-open .admin-mobile-backdrop { display: block; }
        .admin-wrap.nav-open .admin-sidebar { transform: translateX(0); box-shadow: 8px 0 32px rgba(0,0,0,0.5); }

        /* ── Sidebar ── */
        .sidebar-logo      { padding: 20px 20px 16px; border-bottom: 1px solid var(--admin-border); flex-shrink: 0; }
        .sidebar-logo-text { font-size: 20px; font-weight: 700; letter-spacing: 0.12em; color: #FFFFFF; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .sidebar-logo-badge{ font-size: 9px; background: var(--admin-orange); color: #fff; padding: 1px 5px; border-radius: 2px; letter-spacing: 0.1em; font-weight: 600; }
        .sidebar-nav       { flex: 1; padding: 12px 0; overflow-y: auto; }
        .sidebar-section   { padding: 8px 16px 4px; font-size: 10px; font-weight: 600; letter-spacing: 0.12em; color: var(--admin-muted); text-transform: uppercase; }
        .sidebar-link      { display: flex; align-items: center; gap: 10px; padding: 8px 16px; color: #9CA3AF; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 0; transition: background 0.12s, color 0.12s; position: relative; }
        .sidebar-link:hover{ background: rgba(255,255,255,0.04); color: #FFFFFF; }
        .sidebar-link.active { background: rgba(232,80,10,0.12); color: var(--admin-orange); border-left: 2px solid var(--admin-orange); padding-left: 14px; }
        .sidebar-link svg  { flex-shrink: 0; opacity: 0.7; }
        .sidebar-link.active svg { opacity: 1; }
        .sidebar-badge     { margin-left: auto; background: var(--admin-orange); color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 9px; }
        .sidebar-footer    { padding: 16px; border-top: 1px solid var(--admin-border); flex-shrink: 0; }

        /* ── Topbar ── */
        .topbar-title      { font-size: 15px; font-weight: 600; color: #FFFFFF; line-height: 1.35; word-break: break-word; overflow-wrap: anywhere; }
        .topbar-breadcrumb { font-size: 12px; color: var(--admin-muted); margin-top: 1px; line-height: 1.4; word-break: break-word; overflow-wrap: anywhere; }
        .topbar-actions    { display: flex; align-items: center; gap: 12px; }
        .topbar-user       { display: flex; align-items: center; gap: 8px; }
        .topbar-avatar     { width: 32px; height: 32px; border-radius: 50%; background: var(--admin-orange); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; }
        .topbar-username   { font-size: 13px; font-weight: 500; color: #E5E5E5; }

        /* ── Cards / Tables ── */
        .admin-card        { background: var(--admin-surface); border: 1px solid var(--admin-border); border-radius: 4px; }
        .admin-card-header { padding: 16px 20px; border-bottom: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: space-between; }
        .admin-card-title  { font-size: 13px; font-weight: 600; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.06em; margin: 0; line-height: 1.35; word-break: break-word; overflow-wrap: anywhere; }
        .admin-card-body   { padding: 20px; }

        .admin-table       { width: 100%; border-collapse: collapse; }
        .admin-table th    { padding: 10px 14px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--admin-muted); border-bottom: 1px solid var(--admin-border); text-align: left; white-space: nowrap; }
        .admin-table td    { padding: 12px 14px; border-bottom: 1px solid rgba(36,36,36,0.7); font-size: 13px; vertical-align: middle; }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(255,255,255,0.02); }

        /* Scrollable table regions (explicit class or any div wrapping .admin-table) */
        .admin-table-wrap,
        .admin-card > div:has(> table.admin-table) {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        /* ── Forms ── */
        .form-group        { margin-bottom: 20px; }
        .form-label        { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--admin-muted); margin-bottom: 6px; }
        .form-input        { width: 100%; background: #0D0D0D; border: 1px solid var(--admin-border); color: #E5E5E5; padding: 9px 12px; font-size: 13px; border-radius: 3px; font-family: inherit; transition: border-color 0.15s; }
        .form-input:focus  { outline: none; border-color: var(--admin-orange); }
        .form-textarea     { min-height: 140px; resize: vertical; line-height: 1.6; }
        .form-select       { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23737373' d='M1 1l5 5 5-5'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; cursor: pointer; }
        .form-check        { display: flex; align-items: center; gap: 8px; }
        .form-check input  { width: 15px; height: 15px; accent-color: var(--admin-orange); }
        .form-error        { font-size: 12px; color: var(--admin-red); margin-top: 4px; }
        .form-hint         { font-size: 11px; color: var(--admin-muted); margin-top: 4px; }

        /* ── Buttons ── */
        .btn               { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 13px; font-weight: 600; letter-spacing: 0.04em; border-radius: 3px; text-decoration: none; cursor: pointer; border: 1px solid transparent; transition: all 0.15s; font-family: inherit; white-space: nowrap; }
        .btn-primary       { background: var(--admin-orange); color: #fff; border-color: var(--admin-orange); }
        .btn-primary:hover { background: var(--admin-orange-h); border-color: var(--admin-orange-h); }
        .btn-secondary     { background: transparent; color: #C5C1BB; border-color: var(--admin-border); }
        .btn-secondary:hover { background: rgba(255,255,255,0.05); border-color: #404040; color: #fff; }
        .btn-danger        { background: transparent; color: var(--admin-red); border-color: rgba(239,68,68,0.4); }
        .btn-danger:hover  { background: rgba(239,68,68,0.1); }
        .btn-sm            { padding: 5px 10px; font-size: 12px; }
        .btn-xs            { padding: 3px 8px; font-size: 11px; }

        /* ── Badges ── */
        .badge             { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 9px; font-size: 11px; font-weight: 600; letter-spacing: 0.04em; }
        .badge-success     { background: rgba(34,197,94,0.12); color: var(--admin-green); border: 1px solid rgba(34,197,94,0.25); }
        .badge-warning     { background: rgba(234,179,8,0.12);  color: var(--admin-yellow); border: 1px solid rgba(234,179,8,0.25); }
        .badge-danger      { background: rgba(239,68,68,0.12);  color: var(--admin-red);  border: 1px solid rgba(239,68,68,0.25); }
        .badge-info        { background: rgba(59,130,246,0.12); color: var(--admin-blue); border: 1px solid rgba(59,130,246,0.25); }
        .badge-neutral     { background: rgba(115,115,115,0.15);color: #9CA3AF; border: 1px solid var(--admin-border); }
        .badge-orange      { background: rgba(232,80,10,0.12);  color: var(--admin-orange); border: 1px solid rgba(232,80,10,0.3); }

        /* ── Stat cards ── */
        .stat-card         { background: var(--admin-surface); border: 1px solid var(--admin-border); border-radius: 4px; padding: 20px; min-width: 0; display: flex; align-items: flex-start; gap: 14px; }
        .stat-label        { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--admin-muted); margin-bottom: 8px; }
        .stat-value        { font-size: 28px; font-weight: 700; color: #FFFFFF; line-height: 1.15; word-break: break-word; overflow-wrap: anywhere; }
        .stat-sub          { font-size: 12px; color: var(--admin-muted); margin-top: 6px; word-break: break-word; overflow-wrap: anywhere; }
        .stat-icon         { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .stat-card > div:not(.stat-icon) { min-width: 0; flex: 1 1 auto; }

        /* ── Flash alerts ── */
        .flash             { padding: 12px 16px; border-radius: 3px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .flash-success     { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); color: #86EFAC; }
        .flash-error       { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); color: #FCA5A5; }
        .flash-warning     { background: rgba(234,179,8,0.1); border: 1px solid rgba(234,179,8,0.25); color: #FDE047; }
        a.flash             { white-space: normal; text-align: left; min-width: 0; }

        /* ── Misc ── */
        .text-muted        { color: var(--admin-muted); }
        .text-orange       { color: var(--admin-orange); }
        .text-sm           { font-size: 12px; }
        .mono              { font-family: 'JetBrains Mono', monospace; font-size: 12px; }
        .divider           { border: none; border-top: 1px solid var(--admin-border); margin: 20px 0; }
        .page-header       { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; min-width: 0; }
        .page-header h1    { font-size: 20px; font-weight: 700; color: #FFFFFF; margin: 0; line-height: 1.25; word-break: break-word; overflow-wrap: anywhere; max-width: 100%; }
        .page-header > div:first-child { min-width: 0; max-width: 100%; }
        .grid-2            { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3            { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .grid-4            { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .image-thumb       { width: 48px; height: 48px; object-fit: cover; border-radius: 3px; background: #1A1A1A; border: 1px solid var(--admin-border); }

        /* Product editor tabs, sticky footer, leads waitlist grid */
        .admin-tabs {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--admin-border);
            margin-bottom: 24px;
            min-width: 0;
        }
        .admin-form-footer {
            position: sticky;
            bottom: 0;
            z-index: 10;
            background: var(--admin-surface);
            border-top: 1px solid var(--admin-border);
            padding: 12px 0;
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .admin-form-auto-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fill, minmax(min(200px, 100%), 1fr));
            align-items: end;
        }
        .admin-form-span-2 {
            grid-column: span 2;
            min-width: 0;
        }

        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-4 > * { min-width: 0; }
            .grid-3 { grid-template-columns: 1fr 1fr; }
            .stat-value { font-size: clamp(1.125rem, 2.2vw + 0.8rem, 1.75rem); }
        }
        @media (max-width: 768px) {
            .admin-nav-toggle { display: inline-flex; }
            html[dir="ltr"] .admin-main,
            html[dir="rtl"] .admin-main { margin-left: 0; margin-right: 0; }
            .admin-content { padding: 16px 14px; }
            .admin-topbar  { padding: 10px 12px; }
            .topbar-actions { gap: 8px; }
            .topbar-username { display: none; }
            .topbar-actions .btn-sm { padding: 6px 10px; font-size: 11px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .grid-2 > * { min-width: 0; }
            .grid-4 { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: stretch; gap: 14px; }
            .page-header > div { min-width: 0; }
            .page-header > div:first-child[style*="display:flex"],
            .page-header > div:first-child[style*="display: flex"] {
                flex-wrap: wrap !important;
                align-items: center !important;
            }
            .page-header h1 {
                min-width: 0;
                overflow-wrap: anywhere;
                word-break: break-word;
                line-height: 1.25;
            }
            .page-header > div:last-child {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: stretch;
            }
            .page-header > div:last-child .btn {
                flex: 1 1 148px;
                justify-content: center;
                min-width: 0;
            }
            .page-header > div:last-child form {
                flex: 1 1 148px;
                min-width: 0;
                display: flex;
            }
            .page-header > div:last-child form .btn {
                width: 100%;
            }
            .page-header > a.btn {
                width: 100%;
                justify-content: center;
                align-self: stretch;
            }
            .topbar-actions {
                flex-wrap: wrap;
                justify-content: flex-end;
                row-gap: 8px;
                max-width: 100%;
            }
            .admin-topbar { row-gap: 10px; }
            .admin-topbar-start > div {
                min-width: 0;
                flex: 1 1 auto;
            }
            .topbar-title { font-size: clamp(14px, 3.5vw, 15px); }
            .admin-card-header > div {
                width: 100%;
                max-width: 100%;
                min-width: 0;
                flex-wrap: wrap !important;
            }
            .admin-card-header > a.btn {
                width: 100%;
                max-width: 100%;
                justify-content: center;
            }
            .admin-card-header > div .btn {
                flex: 1 1 120px;
                justify-content: center;
                min-width: 0;
            }
            .stat-value {
                font-size: clamp(1.05rem, 5.5vw, 1.55rem);
            }
            .grid-4 > * { min-width: 0; }
            .admin-tabs {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                flex-wrap: nowrap;
                scrollbar-width: thin;
            }
            .admin-tabs button {
                flex-shrink: 0;
                white-space: nowrap;
            }
            .admin-form-auto-grid {
                grid-template-columns: 1fr !important;
            }
            .admin-form-span-2 {
                grid-column: 1 / -1 !important;
            }
            .admin-form-footer {
                justify-content: stretch;
                padding-left: 4px;
                padding-right: 4px;
            }
            .admin-form-footer .btn {
                flex: 1 1 140px;
                justify-content: center;
            }
            .admin-card-body > div[style*="justify-content: space-between"] {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 4px !important;
            }
            .admin-card-body > div[style*="justify-content: space-between"] > span:last-child {
                width: 100%;
                text-align: left !important;
                word-break: break-word;
                overflow-wrap: anywhere;
            }
            .admin-card [style*="min-width: 200px"] { min-width: 0 !important; }
            .admin-card [style*="min-width: 160px"] { min-width: 0 !important; }
            .admin-content a.flash[style*="flex: 1"] {
                flex: 1 1 100% !important;
                min-width: 0 !important;
            }
            .admin-card-header { flex-direction: column; align-items: flex-start !important; gap: 10px; }
            /* Admin tables → card stack on mobile (labels from data-label, set in app.js) */
            .admin-table-wrap,
            .admin-card > div:has(> table.admin-table) {
                overflow-x: visible;
            }
            table.admin-table {
                min-width: 0 !important;
                width: 100%;
                display: block;
                border-collapse: separate;
                border-spacing: 0;
            }
            .admin-table thead { display: none; }
            .admin-table tbody { display: block; }
            .admin-table tbody tr {
                display: block;
                margin-bottom: 12px;
                padding: 2px 2px 10px;
                background: rgba(255, 255, 255, 0.025);
                border: 1px solid var(--admin-border);
                border-radius: 6px;
            }
            .admin-table tbody tr:last-child { margin-bottom: 0; }
            .admin-table tbody td {
                word-break: break-word;
                white-space: normal;
                border-bottom: 1px solid rgba(36, 36, 36, 0.9);
                vertical-align: top;
            }
            .admin-table tbody tr td:last-child { border-bottom: none; }
            .admin-table tbody td[data-label] {
                display: block;
                padding: 12px 14px !important;
            }
            .admin-table tbody td[data-label]::before {
                display: block;
                content: attr(data-label);
                font-size: 10px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--admin-muted);
                margin-bottom: 6px;
            }
            .admin-table tbody td:not([data-label]):not([colspan]) {
                display: block;
                padding: 10px 14px !important;
                text-align: right;
                border-bottom: 1px solid rgba(36, 36, 36, 0.9);
            }
            .admin-table tbody td[colspan] {
                border: none !important;
                text-align: center !important;
                padding: 28px 16px !important;
            }
            .admin-table tbody td[colspan]::before {
                content: none !important;
                display: none !important;
            }
            .admin-table tbody tr:hover td { background: transparent; }
            .admin-table tfoot {
                display: block;
                margin-top: 14px;
                padding-top: 10px;
                border-top: 1px solid var(--admin-border);
            }
            .admin-table tfoot tr {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
                align-items: baseline;
                gap: 8px 18px;
                padding: 5px 0;
            }
            .admin-table tfoot td {
                border: none !important;
                padding: 5px 0 !important;
            }
            .admin-table tfoot td:first-child:not(:only-child) {
                flex: 1 1 100px;
                text-align: right !important;
                min-width: 0;
            }
            .admin-table tfoot td:last-child:not(:only-child) {
                flex: 0 0 auto;
                text-align: right !important;
                white-space: nowrap;
            }
            .admin-content nav { max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 4px; }
            .admin-content nav .flex { flex-wrap: wrap; justify-content: center; row-gap: 6px; }
            .admin-card > div[style*="border-top: 1px solid var(--admin-border)"][style*="justify-content: space-between"] {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 12px !important;
            }
            .stat-card { padding: 16px; }
            .stat-value { font-size: 22px; }
        }
        @media (max-width: 480px) {
            .admin-content { padding: 12px 10px; }
            .admin-topbar {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            .admin-topbar-start {
                width: 100%;
                flex: none;
                max-width: 100%;
            }
            .topbar-actions {
                width: 100%;
                justify-content: stretch;
            }
            .topbar-actions .topbar-user {
                flex: 1 1 100%;
                justify-content: center;
            }
            .topbar-actions > a.btn-sm,
            .topbar-actions > form {
                flex: 1 1 0;
                min-width: 0;
            }
            .topbar-actions > form .btn-sm {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <style>[x-cloak]{display:none!important;}</style>
    @stack('head')
</head>
<body>
<div
    class="admin-wrap"
    x-data="{ navOpen: false }"
    x-init="$watch('navOpen', v => { document.body.style.overflow = v ? 'hidden' : '' })"
    :class="{ 'nav-open': navOpen }"
    @keydown.window.escape="navOpen = false"
>
    <div
        class="admin-mobile-backdrop"
        x-show="navOpen"
        x-cloak
        @click="navOpen = false"
        aria-hidden="true"
    ></div>

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main --}}
    <div class="admin-main">

        {{-- Topbar --}}
        <header class="admin-topbar">
            <div class="admin-topbar-start">
                <button
                    type="button"
                    class="admin-nav-toggle"
                    @click="navOpen = ! navOpen"
                    :aria-expanded="navOpen"
                    aria-controls="admin-sidebar"
                    :aria-label="navOpen ? @js(__('admin.layout.menu_close')) : @js(__('admin.layout.menu_open'))"
                >
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div style="min-width:0;">
                    <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
                    @hasSection('breadcrumb')
                    <div class="topbar-breadcrumb">@yield('breadcrumb')</div>
                    @endif
                </div>
            </div>
            <div class="topbar-actions">
                <div class="flex items-center gap-1 border border-[var(--admin-border)] rounded overflow-hidden" role="group" aria-label="Admin language">
                    <a href="{{ route('admin.locale.switch', 'en') }}"
                       class="px-2.5 py-1.5 text-[11px] font-semibold no-underline {{ $adminLocale === 'en' ? 'bg-[var(--admin-orange)] text-white' : 'text-[var(--admin-muted)] hover:text-white' }}">{{ __('admin.layout.lang_en') }}</a>
                    <a href="{{ route('admin.locale.switch', 'ar') }}"
                       class="px-2.5 py-1.5 text-[11px] font-semibold no-underline {{ $adminLocale === 'ar' ? 'bg-[var(--admin-orange)] text-white' : 'text-[var(--admin-muted)] hover:text-white' }}">{{ __('admin.layout.lang_ar') }}</a>
                </div>
                <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" target="_blank" style="gap:5px;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    {{ __('admin.layout.view_store') }}
                </a>
                <div class="topbar-user">
                    <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="topbar-username">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">{{ __('admin.layout.sign_out') }}</button>
                </form>
            </div>
        </header>

        {{-- Content --}}
        <main class="admin-content">

            @if(session('success'))
            <div class="flash flash-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="flash flash-error">✕ {{ session('error') }}</div>
            @endif
            @if($errors->any())
            <div class="flash flash-error">
                <div>
                    <strong>Please fix the following:</strong>
                    <ul style="margin: 6px 0 0; padding-left: 16px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @yield('content')

        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
