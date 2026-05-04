@extends('admin.layouts.app')

@section('title', 'Quiz #' . $session->id)
@section('page_title', 'Skin Quiz #' . $session->id)
@section('breadcrumb', 'Admin / Skin Quiz / #' . $session->id)

@section('content')

@php
    $lead = $session->lead;
    $answers = $session->answers ?? [];
@endphp

<div class="page-header">
    <div style="display:flex; align-items:center; gap: 14px;">
        <a href="{{ route('admin.quiz-responses.index') }}" class="btn btn-secondary btn-sm">← Back</a>
        <h1>Quiz response</h1>
    </div>
    @if($lead)
    <a href="{{ route('admin.leads.index', ['search' => $lead->email]) }}" class="btn btn-secondary btn-sm">Find in Leads</a>
    @endif
</div>

<div class="grid-2" style="gap: 24px; align-items: start;">

    <div class="admin-card">
        <div class="admin-card-header"><h2 class="admin-card-title">Contact</h2></div>
        <div style="padding: 20px;">
            @if($lead)
            <dl style="margin: 0;">
                <div style="padding: 8px 0; border-bottom: 1px solid var(--admin-border);">
                    <dt class="text-muted text-xs uppercase" style="margin-bottom: 4px;">Email</dt>
                    <dd style="margin: 0; font-weight: 600;">{{ $lead->email }}</dd>
                </div>
                <div style="padding: 8px 0; border-bottom: 1px solid var(--admin-border);">
                    <dt class="text-muted text-xs uppercase" style="margin-bottom: 4px;">Language</dt>
                    <dd style="margin: 0;">{{ strtoupper($lead->preferred_language ?? 'EN') }}</dd>
                </div>
                <div style="padding: 8px 0;">
                    <dt class="text-muted text-xs uppercase" style="margin-bottom: 4px;">Submitted</dt>
                    <dd style="margin: 0;">{{ $session->created_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
            @else
            <p class="text-muted">No linked lead.</p>
            @endif
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><h2 class="admin-card-title">Skin profile</h2></div>
        <div style="padding: 20px;">
            <p style="margin: 0 0 8px; font-size: 18px; font-weight: 600; color: var(--admin-orange);">{{ $session->skin_profile ?? '—' }}</p>
            @if($lead && !empty($lead->quiz_results['profile']['desc_en']))
            <p class="text-muted text-sm" style="margin: 0;">{{ $lead->quiz_results['profile']['desc_en'] }}</p>
            @endif
        </div>
    </div>

    <div class="admin-card" style="grid-column: 1 / -1;">
        <div class="admin-card-header"><h2 class="admin-card-title">Quiz answers</h2></div>
        <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Answer (EN)</th>
                    <th>Raw value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $idx => $q)
                @php $val = $answers[$idx] ?? $answers[(string) $idx] ?? null; @endphp
                <tr>
                    <td class="mono text-sm">{{ $idx + 1 }}</td>
                    <td>{{ $q['en'] }}</td>
                    <td>{{ $val ? ($q['options'][$val]['en'] ?? $val) : '—' }}</td>
                    <td class="mono text-sm text-muted">{{ $val ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    @php
        $ids = $session->recommended_product_ids ?? [];
        $byId = $ids ? \App\Models\Product::whereIn('id', $ids)->get()->keyBy('id') : collect();
        $recProducts = collect($ids)->map(fn ($id) => $byId->get($id))->filter();
    @endphp
    @if($recProducts->isNotEmpty())
    <div class="admin-card" style="grid-column: 1 / -1;">
        <div class="admin-card-header"><h2 class="admin-card-title">Recommended products</h2></div>
        <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recProducts as $product)
                <tr>
                    <td class="mono text-sm">{{ $product->sku }}</td>
                    <td>{{ $product->getTranslation('name', 'en', false) ?: $product->name }}</td>
                    <td><a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-xs">Edit product</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif
</div>

@endsection
