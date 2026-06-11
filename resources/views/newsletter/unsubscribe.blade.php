@extends('layouts.app')

@section('seo_title', app()->getLocale() === 'ar' ? 'إلغاء الاشتراك — FERRO' : 'Unsubscribe — FERRO')

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp
<section class="py-24 px-4">
    <div class="max-w-lg mx-auto text-center">
        <h1 class="font-serif text-3xl text-ferro-off-white mb-4">
            {{ $isAr ? 'النشرة الإخبارية' : 'Newsletter' }}
        </h1>
        <p class="{{ $success ? 'text-green-400' : 'text-red-400' }} text-sm">
            {{ $message }}
        </p>
        <a href="{{ route('home') }}" class="btn-primary inline-block mt-8">
            {{ $isAr ? 'العودة للرئيسية' : 'Back to Home' }}
        </a>
    </div>
</section>
@endsection
