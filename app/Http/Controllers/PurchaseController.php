<?php

namespace App\Http\Controllers;

use App\Models\Purchasing\Penerimaan\PenerimaanBarang;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //

    public function pdfPermintaanPembelian(?int $id)
    {
        $permintaan_pembelian = PermintaanPembelian::with(['permintaanBahanWBB', 'details', 'pic', 'pic.createName', 'pic.knowingName'])->findOrFail($id);
        $tanggal = now('Asia/Jakarta')->format('d-m-Y');
        $fileName = 'FO-QKS-PUR-01-01' . ' - ' . $tanggal . '.pdf';

        return $this->streamPdf(
            view: 'pdf.purchasing.pdfPermintaanPembelian',
            data: ['permintaan_pembelian' => $permintaan_pembelian],
            filename: $fileName
        );
    }

    public function pdfPenerimaanBarang(?int $id)
    {
        $penerimaan_barang = PenerimaanBarang::findOrFail($id);
        $tanggal = now('Asia/Jakarta')->format('d-m-Y');
        $fileName = 'FO-QKS-PUR-01-03' . ' - ' . $tanggal . '.pdf';


        return $this->streamPdf(
            view: 'pdf.purchasing.pdfPenerimaanBarang',
            data: ['penerimaan_barang' => $penerimaan_barang],
            filename: $fileName
        );
    }
}
