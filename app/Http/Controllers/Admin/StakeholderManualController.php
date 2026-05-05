<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class StakeholderManualController extends Controller
{
    /**
     * Download the FERRO stakeholder & operations manual as a PDF (English).
     */
    public function download(): Response
    {
        $pdf = Pdf::loadView('pdf.stakeholder-manual', [
            'generatedAt' => now()->format('d F Y'),
            'appUrl' => rtrim((string) config('app.url'), '/'),
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('dpi', 120)
            ->setOption('defaultFont', 'DejaVu Sans');

        $filename = 'FERRO_Stakeholder_Manual_'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }
}
