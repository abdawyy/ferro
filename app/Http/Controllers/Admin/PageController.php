<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('sort_order')->orderBy('created_at')->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.edit', ['page' => new Page()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Page::create($data);
        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validated($request));
        return redirect()->route('admin.pages.index')->with('success', 'Page "' . ($page->getTranslation('title', 'en') ?? $page->slug) . '" saved.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'slug'              => 'required|string|max:200|regex:/^[a-z0-9\-]+$/',
            'title_en'          => 'required|string|max:300',
            'title_ar'          => 'nullable|string|max:300',
            'content_en'        => 'required|string',
            'content_ar'        => 'nullable|string',
            'meta_title_en'     => 'nullable|string|max:300',
            'meta_title_ar'     => 'nullable|string|max:300',
            'meta_description_en' => 'nullable|string|max:500',
            'meta_description_ar' => 'nullable|string|max:500',
            'is_published'      => 'boolean',
            'sort_order'        => 'integer|min:0',
            'template'          => 'in:default,full-width,landing',
        ]);

        return [
            'slug'             => $request->slug,
            'title'            => ['en' => $request->title_en, 'ar' => $request->title_ar],
            'content'          => ['en' => $request->content_en, 'ar' => $request->content_ar],
            'meta_title'       => ['en' => $request->meta_title_en, 'ar' => $request->meta_title_ar],
            'meta_description' => ['en' => $request->meta_description_en, 'ar' => $request->meta_description_ar],
            'is_published'     => $request->boolean('is_published'),
            'sort_order'       => $request->input('sort_order', 0),
            'template'         => $request->input('template', 'default'),
        ];
    }
}
