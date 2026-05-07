@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('auth_forgot_password');
@endphp

@section('seo_title', $seo['title'])
@section('seo_description', $seo['description'])
@section('seo_keywords', $seo['keywords'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@section('content')

<div class="min-h-screen flex items-center justify-center pt-[72px] pb-16 px-4">

    <div class="w-full max-w-md relative z-10">

        <div class="text-center mb-10">
            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center gap-3 group"
               aria-label="{{ $isAr ? 'فيرو — الرئيسية' : 'FERRO Home' }}">
                <svg class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 text-ferro-orange group-hover:scale-105 transition-transform duration-300"
                     viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z" fill="currentColor"/>
                </svg>
                <span class="font-display text-2xl sm:text-3xl tracking-[0.2em] text-ferro-white uppercase">FERRO</span>
            </a>
            <p class="text-ferro-ash text-body-sm mt-4">
                {{ $isAr ? 'أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين.' : 'Enter your email and we will send you a reset link.' }}
            </p>
        </div>

        <div class="bg-ferro-obsidian border border-ferro-carbon p-8" style="border-radius: 2px;"
             x-data="{ loading: false }">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-body-sm" style="border-radius: 2px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-400 text-body-sm" style="border-radius: 2px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" @submit="loading = true" novalidate>
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="form-label" for="email">{{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input-ferro @error('email') border-red-500/50 @enderror"
                            value="{{ old('email') }}"
                            placeholder="{{ $isAr ? 'بريدك@email.com' : 'you@email.com' }}"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn-primary w-full clip-luxury-md"
                        :disabled="loading"
                        :class="{ 'opacity-70 cursor-not-allowed': loading }"
                    >
                        <span x-show="!loading">{{ $isAr ? 'إرسال رابط الاستعادة' : 'Email reset link' }}</span>
                        <span x-show="loading" class="flex items-center justify-center gap-2" x-cloak>
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            {{ $isAr ? 'جاري الإرسال...' : 'Sending...' }}
                        </span>
                    </button>
                </div>
            </form>

            <p class="text-center text-ferro-ash text-body-sm mt-6">
                <a href="{{ route('login') }}" class="text-ferro-orange hover:underline underline-offset-2">
                    {{ $isAr ? 'العودة لتسجيل الدخول' : 'Back to sign in' }}
                </a>
            </p>
        </div>
    </div>
</div>

@endsection
