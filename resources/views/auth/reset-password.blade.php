@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr ? 'تعيين كلمة مرور جديدة — فيرو' : 'Set New Password — FERRO')

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
                {{ $isAr ? 'اختر كلمة مرور جديدة لحسابك.' : 'Choose a new password for your account.' }}
            </p>
        </div>

        <div class="bg-ferro-obsidian border border-ferro-carbon p-8" style="border-radius: 2px;"
             x-data="{ loading: false, showPw: false, showPw2: false }">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-body-sm" style="border-radius: 2px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" @submit="loading = true" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-5">
                    <div>
                        <label class="form-label" for="email">{{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input-ferro @error('email') border-red-500/50 @enderror"
                            value="{{ old('email', $email) }}"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div>
                        <label class="form-label" for="password">{{ $isAr ? 'كلمة المرور الجديدة' : 'New password' }}</label>
                        <div class="relative">
                            <input
                                :type="showPw ? 'text' : 'password'"
                                id="password"
                                name="password"
                                class="input-ferro pe-12 @error('password') border-red-500/50 @enderror"
                                autocomplete="new-password"
                                required
                                minlength="8"
                            >
                            <button type="button" @click="showPw = !showPw"
                                    class="absolute inset-y-0 end-4 flex items-center text-ferro-ash hover:text-ferro-silver transition-colors"
                                    :aria-label="showPw ? '{{ $isAr ? 'إخفاء' : 'Hide' }}' : '{{ $isAr ? 'إظهار' : 'Show' }}'">
                                <svg x-show="!showPw" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <svg x-show="showPw" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="form-label" for="password_confirmation">{{ $isAr ? 'تأكيد كلمة المرور' : 'Confirm password' }}</label>
                        <div class="relative">
                            <input
                                :type="showPw2 ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="input-ferro pe-12"
                                autocomplete="new-password"
                                required
                                minlength="8"
                            >
                            <button type="button" @click="showPw2 = !showPw2"
                                    class="absolute inset-y-0 end-4 flex items-center text-ferro-ash hover:text-ferro-silver transition-colors"
                                    :aria-label="showPw2 ? '{{ $isAr ? 'إخفاء' : 'Hide' }}' : '{{ $isAr ? 'إظهار' : 'Show' }}'">
                                <svg x-show="!showPw2" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <svg x-show="showPw2" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="btn-primary w-full clip-luxury-md"
                        :disabled="loading"
                        :class="{ 'opacity-70 cursor-not-allowed': loading }"
                    >
                        <span x-show="!loading">{{ $isAr ? 'تحديث كلمة المرور' : 'Update password' }}</span>
                        <span x-show="loading" class="flex items-center justify-center gap-2" x-cloak>
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            {{ $isAr ? 'جاري الحفظ...' : 'Saving...' }}
                        </span>
                    </button>
                </div>
            </form>

            <p class="text-center text-ferro-ash text-body-sm mt-6">
                <a href="{{ route('login') }}" class="text-ferro-orange hover:underline underline-offset-2">
                    {{ $isAr ? 'تسجيل الدخول' : 'Sign in' }}
                </a>
            </p>
        </div>
    </div>
</div>

@endsection
