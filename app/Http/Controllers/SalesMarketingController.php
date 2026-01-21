<?php

namespace App\Http\Controllers;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;

class SalesMarketingController extends Controller
{
    //
    public function previewSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'pic', 'details.product', 'details.file', 'pic.signedName', 'pic.acceptedName', 'pic.acknowledgeName'])->findOrFail($id);

        return view('pdf.sales.pdfSpecProduct', compact('spesifikasi'));
    }

    public function downloadFileSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['details.file'])->findOrFail($id);

        $detail = $spesifikasi->details->first();

        $filePath = $detail->file->file_path;

        $fullPath = storage_path('app/public/' . $filePath);

        return response()->download($fullPath);
    }

    public function pdfSPKMarketing($id)
    {
        $spk_mkt = SPKMarketing::with(['spesifikasiProduct', 'pic', 'pic.createName', 'pic.receiveName'])->findOrFail($id);

        return view('pdf.sales.pdfSPKMarketing', compact('spk_mkt'));
    }
}
