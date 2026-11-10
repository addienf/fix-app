<?php

namespace App\Http\Controllers;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;

class SalesMarketingController extends Controller
{
    //
    public function previewSpesifikasiProduct(?int $id)
    {
        $spesifikasi = SpesifikasiProduct::with(['urs.company', 'pic', 'details.product', 'details.file', 'pic.signedName', 'pic.acceptedName', 'pic.acknowledgeName'])->findOrFail($id);

        $this->authorize('view', $spesifikasi);

        return $this->viewPdf('pdf.sales.pdfSpecProduct', compact('spesifikasi'));
    }

    public function downloadFileSpesifikasiProduct(?int $id)
    {
        $spesifikasi = SpesifikasiProduct::with(['details.file'])->findOrFail($id);
        $filePath = $spesifikasi->details->first()?->file?->file_path;

        return $this->downloadAttachment($spesifikasi, $filePath);
    }

    public function pdfSPKMarketing(?int $id)
    {
        $spk_mkt = SPKMarketing::with([
            'spesifikasiProduct.details.product',
            'spesifikasiProduct.urs.company',
            'pic',
            'pic.createName',
            'pic.receiveName'
        ])->findOrFail($id);

        $filename = 'SPK-Marketing-' . str_replace(['/', '\\', ' '], '-', $spk_mkt->no_spk) . '.pdf';

        return $this->streamPdf(
            view: 'pdf.sales.pdfSPKMarketing',
            data: ['spk_mkt' => $spk_mkt],
            filename: $filename,
            orientation: 'landscape'
        );
    }
}
