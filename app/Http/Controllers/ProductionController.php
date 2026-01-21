<?php

namespace App\Http\Controllers;

use App\Models\Production\Jadwal\JadwalProduksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProductionController extends Controller
{
    //
    public function pdfJadwalProduksi($id)
    {
        // $jadwal = JadwalProduksi::with(['spk', 'details', 'pic', 'sumbers', 'identifikasiProduks', 'timelines', 'pic.createName', 'pic.approveName'])->findOrFail($id);

        // $pdf = Pdf::loadView('pdf.production.pdfJadwalProduksi', compact('jadwal'))
        //     ->setPaper('A4', 'portrait');

        // return $pdf->stream('jadwal-produksi.pdf');
        $jadwalProduksi = JadwalProduksi::with([
            'spk',
            'details',
            'pic',
            'sumbers',
            'identifikasiProduks',
            'timelines',
            'pic.createName',
            'pic.approveName',
        ])->findOrFail($id);

        $tanggal = Carbon::parse($jadwalProduksi->tanggal)->format('Y-m-d');
        $noSuratSafe = str_replace(['/', '\\', ' '], '-', $jadwalProduksi->no_surat);
        $baseName = $noSuratSafe . '-' . $tanggal;

        $pdf = Pdf::loadView('pdf.production.pdfJadwalProduksi', [
            'jadwal' => $jadwalProduksi
        ])->setPaper('a4', 'portrait');

        $pdfName = $baseName . '.pdf';
        $pdfTempPath = storage_path('app/temp/' . $pdfName);

        if (!file_exists(dirname($pdfTempPath))) {
            mkdir(dirname($pdfTempPath), 0755, true);
        }

        file_put_contents($pdfTempPath, $pdf->output());

        $zipRelativePath = 'jadwal-produksi/' . $baseName . '.zip';
        $zipFullPath = storage_path('app/public/' . $zipRelativePath);

        if (!file_exists(dirname($zipFullPath))) {
            mkdir(dirname($zipFullPath), 0755, true);
        }

        $zip = new ZipArchive;
        $zip->open($zipFullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($pdfTempPath, $pdfName);

        $filePath = $jadwalProduksi->file_upload;

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $fullPath = storage_path('app/public/' . $filePath);
            $zip->addFile($fullPath, 'Attachments/' . basename($filePath));
        }

        $zip->close();

        return Storage::disk('public')->download($zipRelativePath);
    }

    // public function downloadJadwalProduksi($id)
    // {
    //     $jadwalProduksi = JadwalProduksi::findOrFail($id);

    //     $filePath = $jadwalProduksi->file_upload;

    //     if (!$filePath || !Storage::disk('public')->exists($filePath)) {
    //         return response()->json(['message' => 'File not found'], 404);
    //     }

    //     return response()->download(storage_path('app/public/' . $filePath));
    // }
}
