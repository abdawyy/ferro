@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr ? 'تسجيل الدخول — فيرو' : 'Sign In — FERRO')

@section('content')

<div class="min-h-screen flex items-center justify-center pt-[72px] pb-16 px-4">

    {{-- Background glow --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_30%,rgba(232,80,10,0.06)_0%,transparent_60%)]" aria-hidden="true"></div>

    <div class="w-full max-w-md relative z-10">

        {{-- Logo --}}
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3">
                <svg class="w-10 h-10 text-ferro-orange" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                </svg>
                <span class="font-display text-2xl tracking-[0.3em] text-ferro-white uppercase">FERRO</span>
            </a>
            <p class="text-ferro-ash text-body-sm mt-4">
                {{ $isAr ? 'تسجيل الدخول إلى حسابك' : 'Sign in to your account' }}
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-ferro-obsidian border border-ferro-carbon p-8" style="border-radius: 2px;"
             x-data="loginForm()">

            {{-- Session errors --}}
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

            <form method="POST" action="{{ route('login') }}" @submit="loading = true" novalidate>
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="form-label" for="email">
                            {{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}
                        </label>
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

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="form-label mb-0" for="password">
                                {{ $isAr ? 'كلمة المرور' : 'Password' }}
                            </label>
                            <a href="{{ route('password.request') }}"
                               class="text-ferro-orange text-xs hover:underline underline-offset-2">
                                {{ $isAr ? 'نسيت كلمة المرور؟' : 'Forgot password?' }}
                            </a>
                        </div>
                        <div class="relative">
                            <input
                                :type="showPw ? 'text' : 'password'"
                                id="password"
                                name="password"
                                class="input-ferro pe-12 @error('password') border-red-500/50 @enderror"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required
                            >
                            <button
                                type="button"
                                @click="showPw = !showPw"
                                class="absolute inset-y-0 end-4 flex items-center text-ferro-ash hover:text-ferro-silver transition-colors"
                                :aria-label="showPw ? '{{ $isAr ? 'إخفاء' : 'Hide password' }}' : '{{ $isAr ? 'إظهار' : 'Show password' }}'"
                            >
                                <svg x-show="!showPw" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <svg x-show="showPw" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 accent-ferro-orange"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="text-ferro-silver text-body-sm cursor-pointer">
                            {{ $isAr ? 'تذكرني' : 'Remember me' }}
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="btn-primary w-full clip-luxury-md"
                        :disabled="loading"
                        :class="{ 'opacity-70 cursor-not-allowed': loading }"
                    >
                        <span x-show="!loading">{{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}</span>
                        <span x-show="loading" class="flex items-center justify-center gap-2" x-cloak>
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            {{ $isAr ? 'جاري تسجيل الدخول...' : 'Signing in...' }}
                        </span>
                    </button>
                </div>
            </form>

            <p class="text-center text-ferro-ash text-body-sm mt-6">
                {{ $isAr ? 'ليس لديك حساب؟' : "Don't have an account?" }}
                <a href="{{ route('register') }}" class="text-ferro-orange hover:underline underline-offset-2 ms-1">
                    {{ $isAr ? 'أنشئ حساباً' : 'Create one' }}
                </a>
            </p>
        </div>

        {{-- Back to home --}}
        <p class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5 {{ $isAr ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
                {{ $isAr ? 'العودة إلى الرئيسية' : 'Back to Home' }}
            </a>
        </p>
    </div>
</div>

@endsection

@push('scripts')
<script>
function loginForm() {
    return { loading: false, showPw: false };
}
</script>
@endpush
