{{--
    FERRO — Global Toast Notification System
    Alpine.js powered. showToast(message, type) is globally callable from JS.
    Types: 'success' | 'error' | 'warning' | 'info'
--}}
<div
    x-data="ferroToast()"
    x-on:ferro-toast.window="show($event.detail)"
    class="fixed bottom-6 inset-x-0 z-[9999] flex flex-col items-end gap-3 px-4 sm:px-6 pointer-events-none"
    aria-live="polite"
    aria-atomic="false"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="pointer-events-auto flex items-start gap-3 max-w-sm w-full px-4 py-3.5 shadow-2xl border"
            :class="toastClass(toast.type)"
            style="border-radius: 2px;"
            role="alert"
        >
            {{-- Icon --}}
            <span class="flex-shrink-0 mt-0.5" aria-hidden="true">
                <template x-if="toast.type === 'success'">
                    <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="w-4 h-4 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-4 h-4 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                </template>
            </span>

            {{-- Message --}}
            <p class="flex-1 text-ferro-off-white text-body-sm leading-snug" x-text="toast.message"></p>

            {{-- Dismiss --}}
            <button
                @click="dismiss(toast.id)"
                class="flex-shrink-0 text-ferro-ash hover:text-ferro-white transition-colors mt-0.5"
                aria-label="Dismiss"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Progress bar --}}
            <div class="absolute bottom-0 inset-x-0 h-0.5 bg-ferro-carbon overflow-hidden" style="border-radius: 0 0 2px 2px;">
                <div
                    class="h-full bg-ferro-orange transition-all duration-100 ease-linear"
                    :style="{ width: toast.progress + '%' }"
                ></div>
            </div>
        </div>
    </template>
</div>

<script>
function ferroToast() {
    return {
        toasts: [],
        counter: 0,

        show({ message, type = 'info', duration = 4000 }) {
            const id       = ++this.counter;
            const toast    = { id, message, type, visible: false, progress: 100 };
            this.toasts.push(toast);

            this.$nextTick(() => {
                toast.visible = true;

                // Progress countdown
                const step     = 100 / (duration / 100);
                const interval = setInterval(() => {
                    toast.progress = Math.max(0, toast.progress - step);
                    if (toast.progress <= 0) {
                        clearInterval(interval);
                        this.dismiss(id);
                    }
                }, 100);
            });
        },

        dismiss(id) {
            const t = this.toasts.find(t => t.id === id);
            if (t) {
                t.visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        },

        toastClass(type) {
            const base = 'bg-ferro-obsidian relative overflow-hidden ';
            switch(type) {
                case 'success': return base + 'border-green-500/30';
                case 'error':   return base + 'border-red-500/30';
                case 'warning': return base + 'border-yellow-500/30';
                default:        return base + 'border-ferro-orange/30';
            }
        },
    };
}

// Global helper callable from anywhere: showToast('Message', 'success')
window.showToast = function(message, type = 'info', duration = 4000) {
    window.dispatchEvent(new CustomEvent('ferro-toast', {
        detail: { message, type, duration }
    }));
};
</script>
