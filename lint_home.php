<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$compiler = app(Illuminate\View\Compilers\BladeCompiler::class);
$source   = file_get_contents('resources/views/layouts/app.blade.php');
$compiled = $compiler->compileString($source);

file_put_contents('layout_compiled.php', $compiled);
echo "Written to layout_compiled.php (" . strlen($compiled) . " bytes)\n";

// PHP lint
$out = shell_exec('php -l layout_compiled.php 2>&1');
echo $out;
