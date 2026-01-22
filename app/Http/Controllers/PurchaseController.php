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

        // return Pdf::loadView('pdf.purchasing.pdfPermintaanPembelian', compact('permintaan_pembelian'))
        // ->stream('permintaan_pembelian.pdf');

        // $tanggal = \Carbon\Carbon::parse($berita->tanggal)->format('Y-m-d');
        // $noSurat = str_replace(['/', '\\'], '-', $permintaan_pembelian->permintaanBahanWBB->no_surat);
        // $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        $tanggal = now('Asia/Jakarta')->format('Y-m-d');
        $fileName = 'FO-QKS-PUR-01-01' . ' - ' . $tanggal . '.pdf';

        $pdf = Pdf::loadView('pdf.purchasing.pdfPermintaanPembelian', compact('permintaan_pembelian'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }
}
