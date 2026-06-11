{{-- Fixed atmospheric layer behind storefront content (admin-overridable via storefront_media) --}}
@php
    $media = app(\App\Services\StorefrontMediaService::class);
    $url = ferro_public_url($media->backdropPath());
    $pos = $media->backdropPosition();
@endphp
@if($url)
<div class="ferro-page-backdrop" style="--ferro-page-bg: url('{{ e($url) }}'); --ferro-page-bg-position: {{ e($pos) }};" aria-hidden="true"></div>
@endif
