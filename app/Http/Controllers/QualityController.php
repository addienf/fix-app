<?php

namespace App\Http\Controllers;

use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Illuminate\Http\Request;
use ZipArchive;

class QualityController extends Controller
{
    //
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
        $kelengkapan = KelengkapanMaterialSS::with(['standarisasiDrawing', 'pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        $no_spk = optional(
            $kelengkapan?->standarisasiDrawing
                ?->serahTerimaWarehouse
                ?->perencanaanProduksi
                ?->spk
        )->no_spk ?? '-';

        return view('pdf.quality.pdfKelengkapanMaterialSS', compact('kelengkapan', 'no_spk'));
    }

    public function pdfPengecekanMaterialSS($id)
    {
        $pengecekanSS = PengecekanMaterialSS::with(['kelengkapanMaterial', 'pic', 'detail', 'penyerahan', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        $no_spk = optional(
            $pengecekanSS?->kelengkapanMaterial
                ?->standarisasiDrawing
                ?->serahTerimaWarehouse
                ?->perencanaanProduksi
                ?->spk
        )->no_spk ?? '-';

        return view('pdf.quality.pdfPengecekanMaterialSS', compact('pengecekanSS', 'no_spk'));
    }
}
