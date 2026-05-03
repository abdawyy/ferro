@extends('admin.layouts.app')

@section('title', 'Pages')
@section('page_title', 'Pages / CMS')
@section('breadcrumb', 'Admin / Pages')

@section('content')

<div class="page-header">
    <h1>Pages</h1>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">+ New Page</a>
</div>

<div class="admin-card">
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title (EN)</th>
                    <th>Slug</th>
                    <th>Template</th>
                    <th style="text-align:center;">Sort</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td style="font-weight: 600;">
                        {{ $page->getTranslation('title','en') }}
                        @if($page->getTranslation('title','ar'))
                        <div class="text-muted text-sm" dir="rtl">{{ $page->getTranslation('title','ar') }}</div>
                        @endif
                    </td>
                    <td class="mono text-muted">/{{ $page->slug }}</td>
                    <td><span class="badge badge-neutral">{{ $page->template }}</span></td>
                    <td style="text-align:center;" class="text-muted">{{ $page->sort_order }}</td>
                    <td>
                        @if($page->is_published)
                        <span class="badge badge-success">Published</span>
                        @else
                        <span class="badge badge-neutral">Draft</span>
                        @endif
                    </td>
                    <td class="text-muted text-sm">{{ $page->updated_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-secondary btn-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
                                  onsubmit="return confirm('Delete this page permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 40px; color: #4B4B4B;">
                        No pages yet. <a href="{{ route('admin.pages.create') }}" class="text-orange">Create your first page →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
