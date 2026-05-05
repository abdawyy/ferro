{{--
    Vite assets: explicit <link> / <script> from build/manifest.json in production.
    When `public/hot` exists (npm run dev), falls back to @vite for HMR.
--}}
@if (file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifest = [];
        if (is_file($manifestPath)) {
            $decoded = json_decode((string) file_get_contents($manifestPath), true);
            $manifest = is_array($decoded) ? $decoded : [];
        }
        $cssEntry = $manifest['resources/css/app.css'] ?? null;
        $jsEntry = $manifest['resources/js/app.js'] ?? null;
    @endphp
    @if ($cssEntry && ! empty($cssEntry['file']))
        <link rel="stylesheet" href="{{ asset('build/'.$cssEntry['file']) }}">
    @endif
    @if ($jsEntry && ! empty($jsEntry['css']))
        @foreach ((array) $jsEntry['css'] as $chunk)
            @if ($chunk !== '')
                <link rel="stylesheet" href="{{ asset('build/'.$chunk) }}">
            @endif
        @endforeach
    @endif
    @if ($jsEntry && ! empty($jsEntry['file']))
        <script type="module" src="{{ asset('build/'.$jsEntry['file']) }}"></script>
    @endif
@endif
