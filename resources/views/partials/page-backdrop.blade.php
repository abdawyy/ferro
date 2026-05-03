{{-- Fixed atmospheric layer behind storefront content (see config/ferro.php page_backgrounds) --}}
@php
    $routes = config('ferro.page_backgrounds.backdrop_routes', []);
    $name = request()->route()?->getName();
    $url = null;
    if ($name !== null && array_key_exists($name, $routes)) {
        $url = $routes[$name];
    } elseif (isset($routes['__default'])) {
        $url = $routes['__default'];
    }
@endphp
@if($url)
<div class="ferro-page-backdrop" style="--ferro-page-bg: url('{{ e($url) }}');" aria-hidden="true"></div>
@endif
