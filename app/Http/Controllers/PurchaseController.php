<?php

namespace App\Http\Controllers;

use App\Models\Purchasing\Penerimaan\PenerimaanBarang;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //

    public function pdfPermintaanPembelian($id)
    {
        $permintaan_pembelian = PermintaanPembelian::with(['permintaanBahanWBB', 'details', 'pic', 'pic.createName', 'pic.knowingName'])->findOrFail($id);

        $tanggal = now('Asia/Jakarta')->format('d-m-Y');
        $fileName = 'FO-QKS-PUR-01-01' . ' - ' . $tanggal . '.pdf';

        $logoPath = public_path('asset/logo.png');
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        if (!file_exists($logoPath)) return null;
        $data = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = Pdf::loadView(
            'pdf.purchasing.pdfPermintaanPembelian',
            compact('permintaan_pembelian', 'logoBase64')
        )->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function pdfPenerimaanBarang($id)
    {
        $penerimaan_barang = PenerimaanBarang::findOrFail($id);

        $tanggal = now('Asia/Jakarta')->format('d-m-Y');
        $fileName = 'FO-QKS-PUR-01-03' . ' - ' . $tanggal . '.pdf';

        $logoPath = public_path('asset/logo.png');
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = Pdf::loadView(
            'pdf.purchasing.pdfPenerimaanBarang',
            compact('penerimaan_barang', 'logoBase64')
        )->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }
}
