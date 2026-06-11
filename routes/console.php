<?php

use App\Models\Product;
use App\Support\ProductImageStorage;
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

Artisan::command('ferro:migrate-product-images', function () {
    $migrated = 0;

    Product::withTrashed()->chunkById(50, function ($products) use (&$migrated) {
        foreach ($products as $product) {
            $changed = false;

            if ($product->featured_image && ProductImageStorage::isLegacyStoragePath($product->featured_image)) {
                $new = ProductImageStorage::migrateLegacyPath($product->featured_image, ProductImageStorage::FEATURED_DIR);
                if ($new) {
                    $product->featured_image = $new;
                    $changed = true;
                    $migrated++;
                }
            }

            $gallery = $product->gallery_images ?? [];
            foreach ($gallery as $i => $img) {
                if (! ProductImageStorage::isLegacyStoragePath($img)) {
                    continue;
                }
                $new = ProductImageStorage::migrateLegacyPath($img, ProductImageStorage::GALLERY_DIR);
                if ($new) {
                    $gallery[$i] = $new;
                    $changed = true;
                    $migrated++;
                }
            }

            if ($changed) {
                $product->gallery_images = $gallery;
                $product->saveQuietly();
            }
        }
    });

    $this->info("Migrated {$migrated} image(s) to public/uploads.");
})->purpose('Copy legacy storage product images into public/uploads for shared hosting');
