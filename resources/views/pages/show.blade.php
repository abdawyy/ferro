@extends('layouts.app')

@php
    $locale = app()->getLocale();
    $title = $page->getTranslation('title', $locale)
        ?: $page->getTranslation('title', 'en')
        ?: $page->slug;
    $content = $page->getTranslation('content', $locale)
        ?: $page->getTranslation('content', 'en')
        ?: '';
    $metaTitle = $page->getTranslation('meta_title', $locale)
        ?: $page->getTranslation('meta_title', 'en');
    $metaDescription = $page->getTranslation('meta_description', $locale)
        ?: $page->getTranslation('meta_description', 'en');
    $isAr = $locale === 'ar';
@endphp

@section('seo_title', ($metaTitle ?: $title).' — FERRO')
@section('seo_description', $metaDescription ?: ($isAr
    ? 'معلومات قانونية وسياسات فيرو.'
    : 'FERRO legal information and policies.'))

@section('content')

<section class="relative pt-[72px] min-h-[38vh] flex items-end overflow-hidden">
    <div class="absolute inset-0 z-0 bg-ferro-obsidian">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_20%,rgba(232,80,10,0.12)_0%,transparent_55%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ferro-black via-ferro-black/80 to-transparent"></div>
    </div>
    <div class="container-ferro relative z-10 pb-14 {{ $isAr ? 'text-right' : '' }}">
        <span class="eyebrow">{{ $isAr ? 'معلومات قانونية' : 'Legal' }}</span>
        <h1 class="font-display text-display-xl text-ferro-white max-w-3xl {{ $isAr ? 'ml-auto' : '' }}">
            {{ $title }}
        </h1>
        <p class="text-ferro-silver text-body-sm mt-4 max-w-2xl {{ $isAr ? 'ml-auto' : '' }}">
            {{ $isAr
                ? 'آخر تحديث: يُرجى مراجعة هذه الصفحة بشكل دوري. المحتوى قابل للتحديث من لوحة الإدارة.'
                : 'Last updated: review this page periodically. Content is maintained from the admin portal.' }}
        </p>
    </div>
</section>

<section class="section-pad border-t border-ferro-carbon">
    <div class="container-ferro">
        <article class="max-w-3xl mx-auto reveal {{ $isAr ? 'text-right' : '' }}">
            <div class="prose prose-invert max-w-none
                prose-headings:font-display prose-headings:font-normal prose-headings:tracking-tight
                prose-h2:text-display-lg prose-h2:text-ferro-white prose-h2:mt-12 prose-h2:mb-4
                prose-h3:text-xl prose-h3:text-ferro-white prose-h3:mt-8 prose-h3:mb-3
                prose-p:text-ferro-silver prose-p:text-body-sm prose-p:leading-relaxed
                prose-li:text-ferro-silver prose-li:text-body-sm
                prose-strong:text-ferro-off-white
                prose-a:text-ferro-orange prose-a:no-underline hover:prose-a:underline
                prose-ul:my-4 prose-ol:my-4">
                {!! $content !!}
            </div>
        </article>
    </div>
</section>

@endsection
