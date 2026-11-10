<?php

namespace App\Http\Controllers;

use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use Illuminate\Support\Facades\Storage;

class WarehouseController extends Controller
{
    public function pdfPermintaanBahan(?int $id)
    {
        $permintaan_bahan = PermintaanBahan::with(['permintaanBahanPro', 'permintaanDetails', 'pic', 'pic.dibuatName', 'pic.mengetahuiName', 'pic.diserahkanName'])->findOrFail($id);
        $tanggal = \Carbon\Carbon::parse($permintaan_bahan->tanggal)->format('d-m-Y');
        $noSurat = str_replace(['/', '\\'], '-', $permintaan_bahan->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        return $this->streamPdf(
            view: 'pdf.warehouse.pdfPermintaanBahan',
            data: ['permintaan_bahan' => $permintaan_bahan],
            filename: $fileName
        );
    }

    public function pdfIncomingMaterial(?int $id)
    {
        $incomingMaterial = IncommingMaterial::with(['details', 'pic', 'pic.submitedName', 'pic.receivedName'])->findOrFail($id);
        $tanggal = \Carbon\Carbon::parse($incomingMaterial->tanggal)->format('d-m-Y');
        $fileName = 'FO-QKS-WRH-01-01' . ' - ' . $tanggal . '.pdf';

        return $this->streamPdf(
            view: 'pdf.warehouse.pdfIncomingMaterial',
            data: ['incomingMaterial' => $incomingMaterial],
            filename: $fileName
        );
    }

    public function downloadIncomingMaterial(?int $id)
    {
        $incomingMaterial = IncommingMaterial::findOrFail($id);

        return $this->downloadAttachment($incomingMaterial, $incomingMaterial->file_upload);
    }

    public function pdfPeminjamanAlat(?int $id)
    {
        $peminjaman = PeminjamanAlat::with(['details', 'pic', 'pic.NamaPeminjam'])->findOrFail($id);

        return $this->viewPdf('pdf.warehouse.pdfPeminjamanAlat', compact('peminjaman'));
    }

    public function pdfSerahTerima(?int $id)
    {
        $serah_terima = SerahTerimaBahan::with(['standarisasiDrawing', 'details', 'pic', 'pic.submitName', 'pic.receiveName'])->findOrFail($id);
        $tanggal = \Carbon\Carbon::parse($serah_terima->tanggal)->format('d-m-Y');
        $noSurat = str_replace(['/', '\\'], '-', $serah_terima->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        return $this->streamPdf(
            view: 'pdf.warehouse.pdfSerahTerima',
            data: ['serah_terima' => $serah_terima],
            filename: $fileName
        );
    }

    public function pdfPelabelanQCPassed(?int $id)
    {
        $pelabelan = QCPassed::with(['pic', 'details', 'pic.createdName', 'pic.approvedName', 'productRelease'])->findOrFail($id);

        return $this->viewPdf('pdf.warehouse.pdfPelabelanQCPassed', compact('pelabelan'));
    }
}
