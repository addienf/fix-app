<?php

namespace App\Http\Controllers;

use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class QualityController extends Controller
{
    //
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

    public function pdfStandarisasiDrawing($id)
    {
        $standarisasi = StandarisasiDrawing::with(['serahTerimaWarehouse', 'identitas', 'detail', 'pemeriksaan', 'pic', 'pic.createName', 'pic.checkName'])->findOrFail($id);

        $no_spk = optional(
            $standarisasi->serahTerimaWarehouse
                ?->permintaanBahanProduksi
                ?->jadwalProduksi
                ?->spk
        )->no_spk ?? '-';

        return view('pdf.quality.pdfStandarisasiDrawing', compact('standarisasi', 'no_spk'));
    }

    public function pdfStandarisasiDrawingLampiran($id)
    {
        $standarisasi_lampiran = StandarisasiDrawing::with(['detail'])->findOrFail($id);

        return view('pdf.quality.pdfLampiranStandarisasiDrawing', compact('standarisasi_lampiran'));
    }

    public function downloadZipStandarisasiDrawing($id)
    {
        $standarisasi = StandarisasiDrawing::with(['detail'])->findOrFail($id);

        $zipFileName = 'gambar-produk-' . $standarisasi->id . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

            foreach ($standarisasi->detail->lampiran ?? [] as $gambarPath) {
                $fullPath = storage_path('app/public/' . $gambarPath);
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, basename($gambarPath));
                }
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function pdfKelengkapanMaterialSS($id)
    {
        $kelengkapan = KelengkapanMaterialSS::with(['spkQC', 'pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        $logoBase64 = $this->getBase64Logo();

        $pdf = Pdf::loadView('pdf.quality.pdfKelengkapanMaterialSS', compact('kelengkapan', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('kelengkapan-material.pdf');
    }

    public function pdfPengecekanMaterialSS($id)
    {
        $pengecekanSS = PengecekanMaterialSS::with(['spkQC', 'pic', 'detail', 'penyerahan', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        $no_spk = $pengecekanSS?->spkQC?->spkMarketing?->no_spk ?? '-';

        return view('pdf.quality.pdfPengecekanMaterialSS', compact('pengecekanSS', 'no_spk'));
    }
}
