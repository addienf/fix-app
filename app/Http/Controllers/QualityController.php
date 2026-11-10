<?php

namespace App\Http\Controllers;

use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class QualityController extends Controller
{
    public function pdfStandarisasiDrawing($id)
    {
        $standarisasi = StandarisasiDrawing::with(['serahTerimaWarehouse', 'identitas', 'detail', 'pemeriksaan', 'pic', 'pic.createName', 'pic.checkName'])->findOrFail($id);

        $no_spk = optional(
            $standarisasi->serahTerimaWarehouse
                ?->permintaanBahanProduksi
                ?->jadwalProduksi
                ?->spk
        )->no_spk ?? '-';

        return $this->viewPdf('pdf.quality.pdfStandarisasiDrawing', compact('standarisasi', 'no_spk'));
    }

    public function pdfStandarisasiDrawingLampiran($id)
    {
        $standarisasi_lampiran = StandarisasiDrawing::with(['detail'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfLampiranStandarisasiDrawing', compact('standarisasi_lampiran'));
    }

    public function downloadZipStandarisasiDrawing($id)
    {
        $standarisasi = StandarisasiDrawing::with(['detail'])->findOrFail($id);
        $this->authorize('view', $standarisasi);
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
        $fileName = 'kelengkapan-material.pdf';

        return $this->streamPdf(
            view: 'pdf.quality.pdfKelengkapanMaterialSS',
            data: ['kelengkapan' => $kelengkapan],
            filename: $fileName
        );
    }

    public function pdfPengecekanMaterialSS($id)
    {
        $pengecekanSS = PengecekanMaterialSS::with(['spkQC', 'pic', 'detail', 'penyerahan', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);
        $no_spk = $pengecekanSS?->spkQC?->spkMarketing?->no_spk ?? '-';

        return $this->viewPdf('pdf.quality.pdfPengecekanMaterialSS', compact('pengecekanSS', 'no_spk'));
    }
}
