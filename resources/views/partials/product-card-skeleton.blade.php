{{--
    FERRO Product Card Skeleton
    Shimmer loading placeholder — matches card-product dimensions exactly.
    Used in home.blade.php @empty blocks and during AJAX loading.
--}}
<div class="card-product animate-pulse" aria-hidden="true" aria-label="Loading product">
    {{-- Image placeholder --}}
    <div class="product-image-wrap bg-ferro-carbon">
        <div class="w-full h-full bg-gradient-to-br from-ferro-carbon via-ferro-obsidian to-ferro-carbon
                    animate-[shimmer_1.8s_infinite]"></div>
    </div>

    {{-- Info placeholder --}}
    <div class="p-5 space-y-3">
        {{-- Category line --}}
        <div class="h-2.5 w-16 rounded-sm bg-ferro-carbon"></div>
        {{-- Name --}}
        <div class="h-4 w-3/4 rounded-sm bg-ferro-carbon"></div>
        {{-- Description lines --}}
        <div class="space-y-1.5">
            <div class="h-3 w-full rounded-sm bg-ferro-carbon/70"></div>
            <div class="h-3 w-2/3 rounded-sm bg-ferro-carbon/70"></div>
        </div>
        {{-- Price + button row --}}
        <div class="flex items-center justify-between pt-1">
            <div class="h-4 w-14 rounded-sm bg-ferro-carbon"></div>
            <div class="h-8 w-24 rounded-sm bg-ferro-carbon"></div>
        </div>
    </div>
</div>

@once
@push('head')
<style>
@keyframes shimmer {
    0%   { background-position: -400px 0; }
    100% { background-position:  400px 0; }
}
.animate-\[shimmer_1\.8s_infinite\] {
    background: linear-gradient(
        90deg,
        var(--ferro-carbon) 25%,
        var(--ferro-obsidian) 50%,
        var(--ferro-carbon) 75%
    );
    background-size: 800px 100%;
    animation: shimmer 1.8s infinite linear;
}
</style>
@endpush
@endonce
