<?php
$dirs = ['resources/views/layouts', 'resources/views/partials'];
foreach ($dirs as $dir) {
    foreach (glob("$dir/*.blade.php") as $f) {
        $c = file_get_contents($f);
        $issues = [];
        if (preg_match('/<script[\s\S]*?if\s*\(/', $c) && strpos($c, '@verbatim') === false) {
            $issues[] = 'script-no-verbatim';
        }
        if (preg_match('/@section\s*\(\s*[\x27\x22]\w+[\x27\x22]\s*,\s*\n/', $c)) {
            $issues[] = 'multiline-section';
        }
        if (preg_match('/@for\s*\([^)]*<[^=]/', $c)) {
            $issues[] = 'for-with-lt';
        }
        echo strlen($c) . " bytes | " . ($issues ? implode(', ', $issues) : 'OK') . " | $f\n";
    }
}
