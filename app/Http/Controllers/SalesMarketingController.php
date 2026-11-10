<?php

namespace App\Http\Controllers;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SalesMarketingController extends Controller
{
    //
    public function previewSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['urs.company', 'pic', 'details.product', 'details.file', 'pic.signedName', 'pic.acceptedName', 'pic.acknowledgeName'])->findOrFail($id);

        $this->authorize('view', $spesifikasi);

        return $this->viewPdf('pdf.sales.pdfSpecProduct', compact('spesifikasi'));
    }

    public function downloadFileSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['details.file'])->findOrFail($id);

        $this->authorize('view', $spesifikasi);

        $filePath = $spesifikasi->details->first()?->file?->file_path;

        abort_if(blank($filePath), 404, 'File belum diunggah.');
        abort_unless(Storage::disk('public')->exists($filePath), 404, 'File tidak ditemukan.');

        return Storage::disk('public')->download($filePath);
    }

    public function pdfSPKMarketing($id)
    {
        $spk_mkt = SPKMarketing::with([
            'spesifikasiProduct.details.product',
            'spesifikasiProduct.urs.company',
            'pic',
            'pic.createName',
            'pic.receiveName'
        ])->findOrFail($id);

        // Logo Base64
        $logoPath = public_path('asset/logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Tanda tangan create Base64
        $createSigBase64 = null;
        if ($spk_mkt->pic?->create_signature) {
            $createSigPath = storage_path('app/public/' . $spk_mkt->pic->create_signature);
            if (file_exists($createSigPath)) {
                $type = pathinfo($createSigPath, PATHINFO_EXTENSION);
                $createSigBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($createSigPath));
            }
        }

        // Tanda tangan receive Base64
        $receiveSigBase64 = null;
        if ($spk_mkt->pic?->receive_signature) {
            $receiveSigPath = storage_path('app/public/' . $spk_mkt->pic->receive_signature);
            if (file_exists($receiveSigPath)) {
                $type = pathinfo($receiveSigPath, PATHINFO_EXTENSION);
                $receiveSigBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($receiveSigPath));
            }
        }

        $pdf = Pdf::loadView('pdf.sales.pdfSPKMarketing', compact(
            'spk_mkt',
            'logoBase64',
            'createSigBase64',
            'receiveSigBase64'
        ))->setPaper('a4', 'landscape');

        $filename = 'SPK-Marketing-' . str_replace(['/', '\\', ' '], '-', $spk_mkt->no_spk) . '.pdf';

        return $pdf->stream($filename);
    }
}
