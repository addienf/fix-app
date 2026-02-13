<?php

namespace App\Http\Controllers;

use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WarehouseController extends Controller
{
    private function getBase64Logo()
    {
        $logoPath = public_path('asset/logo.png');

        if (!file_exists($logoPath)) {
            return null;
        }

        $type = pathinfo($logoPath, PATHINFO_EXTENSION);

        return 'data:image/' . $type . ';base64,' .
            base64_encode(file_get_contents($logoPath));
    }
    //
    public function pdfPermintaanBahan($id)
    {
        $permintaan_bahan = PermintaanBahan::with(['permintaanBahanPro', 'permintaanDetails', 'pic', 'pic.dibuatName', 'pic.mengetahuiName', 'pic.diserahkanName'])->findOrFail($id);

        $tanggal = \Carbon\Carbon::parse($permintaan_bahan->tanggal)->format('Y-m-d');
        $noSurat = str_replace(['/', '\\'], '-', $permintaan_bahan->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        // $pdf = Pdf::loadView('pdf.warehouse.pdfPermintaanBahan', compact('permintaan_bahan'))
        //     ->setPaper('a4', 'portrait');
        $pdf = Pdf::loadView(
            'pdf.warehouse.pdfPermintaanBahan',
            [
                'permintaan_bahan' => $permintaan_bahan,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function pdfIncomingMaterial($id)
    {
        $incomingMaterial = IncommingMaterial::with(['permintaanPembelian', 'details', 'pic', 'pic.submitedName', 'pic.receivedName'])->findOrFail($id);

        $tanggal = \Carbon\Carbon::parse($incomingMaterial->tanggal)->format('Y-m-d');
        $fileName = 'FO-QKS-WRH-01-01' . ' - ' . $tanggal . '.pdf';

        // $pdf = Pdf::loadView('pdf.warehouse.pdfIncomingMaterial', compact('incomingMaterial'))
        //     ->setPaper('a4', 'portrait');
        $pdf = Pdf::loadView(
            'pdf.warehouse.pdfIncomingMaterial',
            [
                'incomingMaterial' => $incomingMaterial,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function downloadIncomingMaterial($id)
    {
        $incomingMaterial = IncommingMaterial::findOrFail($id);

        $filePath = $incomingMaterial->file_upload;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {

            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function pdfPeminjamanAlat($id)
    {
        $peminjaman = PeminjamanAlat::with(['details', 'pic', 'pic.NamaPeminjam'])->findOrFail($id);

        return view('pdf.warehouse.pdfPeminjamanAlat', compact('peminjaman'));
    }

    public function pdfSerahTerima($id)
    {
        $serah_terima = SerahTerimaBahan::with(['standarisasiDrawing', 'details', 'pic', 'pic.submitName', 'pic.receiveName'])->findOrFail($id);

        $tanggal = \Carbon\Carbon::parse($serah_terima->tanggal)->format('Y-m-d');
        $noSurat = str_replace(['/', '\\'], '-', $serah_terima->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        // $pdf = Pdf::loadView('pdf.warehouse.pdfSerahTerima', compact('serah_terima'))
        //     ->setPaper('a4', 'portrait');
        $pdf = Pdf::loadView(
            'pdf.warehouse.pdfSerahTerima',
            [
                'serah_terima' => $serah_terima,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function pdfPelabelanQCPassed($id)
    {
        $pelabelan = QCPassed::with(['pic', 'details', 'pic.createdName', 'pic.approvedName', 'productRelease'])->findOrFail($id);

        return view('pdf.warehouse.pdfPelabelanQCPassed', compact('pelabelan'));
    }
}
