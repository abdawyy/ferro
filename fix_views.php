<?php
$files = [
    'resources/views/partials/toast.blade.php',
    'resources/views/partials/waitlist-mini-form.blade.php',
];
    'resources/views/cart.blade.php',
    'resources/views/contact.blade.php',
    'resources/views/quiz.blade.php',
    'resources/views/account/index.blade.php',
    'resources/views/admin/products/edit.blade.php',
    'resources/views/products/index.blade.php',
    'resources/views/products/show.blade.php',
    'resources/views/partials/toast.blade.php',
    'resources/views/partials/waitlist-mini-form.blade.php',
];

foreach ($files as $f) {
    $c = file_get_contents($f);
    if (strpos($c, '@verbatim') !== false) {
        echo "Already fixed: $f\n";
        continue;
    }
    // Wrap <script> blocks inside @push('scripts') with @verbatim
    $new = preg_replace(
        "/@push\('scripts'\)(\r?\n)<script/",
        "@push('scripts')$1@verbatim\n<script",
        $c
    );
    $new = preg_replace(
        "/<\/script>(\r?\n)@endpush/",
        "</script>$1@endverbatim\n@endpush",
        $new
    );
    // Also fix @for with < operator (replace with range-based @foreach)
    $new = preg_replace(
        '/@for\s*\(\s*\$(\w+)\s*=\s*0\s*;\s*\$\w+\s*<\s*(\d+)\s*;[^)]+\)/',
        '@foreach(range(1, $2) as $$1)',
        $new
    );
    $new = str_replace('@endfor', '@endforeach', $new);

    if ($new !== $c) {
        file_put_contents($f, $new);
        echo "Fixed: $f\n";
    } else {
        echo "No change: $f\n";
    }
}
echo "Done.\n";
