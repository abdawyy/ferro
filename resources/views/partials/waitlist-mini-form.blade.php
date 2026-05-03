{{--
    FERRO Waitlist Mini Form (inline / footer variant)
    Used in: footer, hero section, PDP coming-soon overlay
--}}
@php $isAr = app()->getLocale() === 'ar'; @endphp

<form
    id="waitlist-mini-form-{{ $formId ?? 'default' }}"
    class="flex flex-col gap-3"
    x-data="waitlistForm()"
    @submit.prevent="submit"
    novalidate
>
    @csrf
    @if(isset($productId))
        <input type="hidden" name="product_id" value="{{ $productId }}">
    @endif
    <input type="hidden" name="preferred_language" value="{{ app()->getLocale() }}">

    <div>
        <label for="waitlist-email-{{ $formId ?? 'default' }}" class="sr-only">
            {{ $isAr ? 'البريد الإلكتروني' : 'Email address' }}
        </label>
        <input
            type="email"
            id="waitlist-email-{{ $formId ?? 'default' }}"
            name="email"
            x-model="email"
            class="input-ferro"
            :class="{ 'error': errors.email }"
            placeholder="{{ $isAr ? 'بريدك الإلكتروني' : 'your@email.com' }}"
            autocomplete="email"
            required
        >
        <p x-show="errors.email" x-text="errors.email" class="text-red-400 text-xs mt-1.5" x-cloak></p>
    </div>

    <button
        type="submit"
        class="btn-primary w-full"
        :disabled="loading"
        :class="{ 'opacity-70 cursor-not-allowed': loading }"
    >
        <span x-show="!loading">
            {{ $isAr ? 'احجز مكانك' : 'Reserve My Spot' }}
        </span>
        <span x-show="loading" class="flex items-center gap-2" x-cloak>
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            {{ $isAr ? 'جاري التسجيل...' : 'Joining...' }}
        </span>
    </button>

    {{-- Success state --}}
    <div
        x-show="success"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="flex items-center gap-2 text-green-400 text-body-sm"
        x-cloak
    >
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span x-text="successMessage"></span>
    </div>

    <p class="text-ferro-ash text-[11px] leading-relaxed">
        {{ $isAr
            ? 'بالاشتراك، أنت توافق على تلقي إشعارات الإطلاق. لن نشارك بياناتك أبداً.'
            : 'By subscribing you agree to receive launch notifications. We never share your data.' }}
    </p>
</form>

<script>
function waitlistForm() {
    return {
        email: '',
        loading: false,
        success: false,
        successMessage: '',
        errors: {},

        async submit() {
            this.errors = {};
            if (!this.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                this.errors.email = '{{ $isAr ? "يرجى إدخال بريد إلكتروني صحيح" : "Please enter a valid email address" }}';
                return;
            }
            this.loading = true;
            try {
                const formData = new FormData(this.$el);
                const res = await fetch('{{ route('waitlist.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await res.json();
                if (data.success) {
                    this.success = true;
                    this.successMessage = data.message;
                    this.email = '';
                    // Trigger forge glow animation on form
                    this.$el.classList.add('border-forge-glow');
                }
            } catch (e) {
                this.errors.email = '{{ $isAr ? "حدث خطأ. حاول مرة أخرى." : "Something went wrong. Please try again." }}';
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
