<?php

namespace App\Http\Controllers;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Production\SPK\SPKVendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProductionController extends Controller
{
    //
    public function pdfJadwalProduksi($id)
    {
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
        $baseName = $noSuratSafe . ' - ' . $tanggal;

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

    public function pdfSPKVendor($id)
    {
        $vendor = SPKVendor::with(['perencanaanProduksi'])->findOrFail($id);

        return view('pdf.production.pdfSPKVendor', compact('vendor'));
    }

    public function downloadSPKVendor($id)
    {
        $vendor = SPKVendor::findOrFail($id);

        //PDF
        $filePath = $vendor->file_path;
        $pdfFullPath = storage_path('app/public/' . $filePath);

        //Gambar
        $zipFileName = 'lampiran-' . $vendor->id . '.zip';
        $zipDir = storage_path('app/temp');
        $zipPath = $zipDir . '/' . $zipFileName;

        // Buat folder sementara jika belum ada
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

            // Tambahkan file PDF ke dalam folder 'dokumen/' di ZIP
            if (file_exists($pdfFullPath)) {
                $zip->addFile($pdfFullPath, 'dokumen/' . basename($filePath));
            }

            // Tambahkan semua gambar ke dalam folder 'gambar/' di ZIP
            foreach ((array) $vendor->lampiran as $gambarPath) {
                $fullGambarPath = storage_path('app/public/' . $gambarPath);

                if (file_exists($fullGambarPath)) {
                    $zip->addFile($fullGambarPath, 'gambar/' . basename($gambarPath));
                }
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function pdfSPKQuality($id)
    {
        $spk_qc = SPKQuality::with(['penyerahanElectrical', 'details', 'pic', 'pic.createName', 'pic.receiveName'])->findOrFail($id);

        return view('pdf.production.pdfSPKQuality', compact('spk_qc'));
    }

    public function pdfPenyerahanElectrical($id)
    {
        $serahElectrical = PenyerahanElectrical::with(['pengecekanSS', 'sebelumSerahTerima', 'pic', 'penerimaElectrical', 'pic.submitName', 'pic.receiveName', 'pic.knowingName'])->findOrFail($id);

        return view('pdf.production.pdfPenyerahanElectrical', compact('serahElectrical'));
    }

    public function downloadPenyerahanElectrical($id)
    {
        $spesifikasi = PenyerahanElectrical::with(['sebelumSerahTerima'])->findOrFail($id);

        $filePath = $spesifikasi->sebelumSerahTerima->file_pendukung;

        $fullPath = storage_path('app/public/' . $filePath);

        return response()->download($fullPath);
    }

    public function pdfPenyerahanProdukJadi($id)
    {
        $produkJadi = PenyerahanProdukJadi::with(['details', 'pic', 'pic.submitName', 'pic.receiveName'])->findOrFail($id);

        return view('pdf.production.pdfPenyerahanProdukJadi', compact('produkJadi'));
    }
}
