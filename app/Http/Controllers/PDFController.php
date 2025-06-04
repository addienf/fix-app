<?php

namespace App\Http\Controllers;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    //

    public function pdfSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'pic', 'details.product'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.pdfSpecProduct', compact('spesifikasi'));
        return $pdf->stream('spesifikasi-produk.pdf');
    }
}
