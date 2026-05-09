<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StakeholderManualController extends Controller
{
    /**
     * Download the FERRO stakeholder & operations manual as a PDF (English).
     */
    public function download(): Response
    {
        $html = View::make('pdf.stakeholder-manual', [
            'generatedAt' => now()->format('d F Y'),
            'appUrl'      => rtrim((string) config('app.url'), '/'),
        ])->render();

        $mpdf = new Mpdf([
            'mode'        => 'utf-8',
            'format'      => 'A4',
            'orientation' => 'P',
            'margin_top'  => 15,
            'margin_right'=> 15,
            'margin_bottom'=> 15,
            'margin_left' => 15,
        ]);
        $mpdf->WriteHTML($html);

        $filename = 'FERRO_Stakeholder_Manual_'.now()->format('Y-m-d').'.pdf';

        return response()->streamDownload(
            fn () => print($mpdf->Output('', 'S')),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
