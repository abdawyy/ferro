<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ferro:export-stakeholder-manual {--path= : Absolute path for the PDF file}', function () {
    $path = $this->option('path') ?: storage_path('app/FERRO_Stakeholder_Manual.pdf');
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $pdf = Pdf::loadView('pdf.stakeholder-manual', [
        'generatedAt' => now()->format('d F Y'),
        'appUrl' => rtrim((string) config('app.url'), '/'),
    ])
        ->setPaper('a4', 'portrait')
        ->setOption('dpi', 120)
        ->setOption('defaultFont', 'DejaVu Sans');

    file_put_contents($path, $pdf->output());
    $this->info("Wrote: {$path}");
})->purpose('Export the FERRO stakeholder manual PDF to disk (default: storage/app/FERRO_Stakeholder_Manual.pdf)');
