<?php

namespace App\Http\Controllers;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //

    public function pdfPermintaanPembelian($id)
    {
        $permintaan_pembelian = PermintaanPembelian::with(['permintaanBahanWBB', 'details', 'pic', 'pic.createName', 'pic.knowingName'])->findOrFail($id);

        // return view('pdf.purchasing.pdfPermintaanPembelian', compact('permintaan_pembelian'));

        return Pdf::loadView('pdf.purchasing.pdfPermintaanPembelian', compact('permintaan_pembelian'))
            ->stream('permintaan_pembelian.pdf');
    }
}
