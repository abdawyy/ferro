{{-- Fixed atmospheric layer behind storefront content (see config/ferro.php page_backgrounds) --}}
@php
    $routes = config('ferro.page_backgrounds.backdrop_routes', []);
    $positions = config('ferro.page_backgrounds.backdrop_position', []);
    $name = request()->route()?->getName();
    $url = null;
    if ($name !== null && array_key_exists($name, $routes)) {
        $url = $routes[$name];
    } elseif (isset($routes['__default'])) {
        $url = $routes['__default'];
    }
    if ($url !== null && ! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
        $url = asset($url);
    }
    $pos = 'center';
    if ($name !== null && array_key_exists($name, $positions)) {
        $pos = $positions[$name];
    } elseif (isset($positions['__default'])) {
        $pos = $positions['__default'];
    }
@endphp
@if($url)
<div class="ferro-page-backdrop" style="--ferro-page-bg: url('{{ e($url) }}'); --ferro-page-bg-position: {{ e($pos) }};" aria-hidden="true"></div>
@endif
