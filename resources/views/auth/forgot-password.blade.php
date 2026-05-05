@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr ? 'استعادة كلمة المرور — فيرو' : 'Forgot Password — FERRO')

@section('content')

<div class="min-h-screen flex items-center justify-center pt-[72px] pb-16 px-4">

    <div class="w-full max-w-md relative z-10">

        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3">
                <img src="{{ asset('images/brand/ferro-hex-logo.png') }}" alt=""
                     width="96" height="96" class="ferro-brand-photo h-12 w-12 object-contain object-right" loading="eager" decoding="async">
                <span class="font-display text-2xl tracking-[0.3em] text-ferro-white uppercase">FERRO</span>
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
