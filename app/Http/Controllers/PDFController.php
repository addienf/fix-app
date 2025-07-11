<?php

namespace App\Http\Controllers;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Production\SPK\SPKVendor;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PDFController extends Controller
{
    //

    public function pdfSpesifikasiProduct()
    {
        return view('pdf.pdfSpecProduct');
    }

    public function previewSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'pic', 'details.product', 'details.file'])->findOrFail($id);

        return view('pdf.sales.pdfSpecProduct', compact('spesifikasi'));
    }

    public function downloadFileSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['details.file'])->findOrFail($id);

        $detail = $spesifikasi->details->first();

        $filePath = $detail->file->file_path;

        $fullPath = storage_path('app/public/' . $filePath);

        return response()->download($fullPath);
    }

    public function pdfSPKMarketing($id)
    {
        $spk_mkt = SPKMarketing::with(['spesifikasiProduct', 'pic'])->findOrFail($id);

        return view('pdf.sales.pdfSPKMarketing', compact('spk_mkt'));
    }

    public function pdfJadwalProduksi($id)
    {
        $jadwal = JadwalProduksi::with(['spk', 'details', 'pic', 'sumber'])->findOrFail($id);

        return view('pdf.production.pdfJadwalProduksi', compact('jadwal'));
    }

    public function pdfPermintaanAlatBahan($id)
    {
        $permintaan_alat_bahan = PermintaanAlatDanBahan::with(['spk', 'details', 'pic'])->findOrFail($id);

        return view('pdf.production.pdfPermintaanAlatBahan', compact('permintaan_alat_bahan'));
    }

    public function pdfPermintaanBahan($id)
    {
        $permintaan_bahan = PermintaanBahan::with(['permintaanBahanPro', 'details', 'pic'])->findOrFail($id);

        return view('pdf.warehouse.pdfPermintaanBahan', compact('permintaan_bahan'));
    }

    public function pdfPermintaanPembelian($id)
    {
        $permintaan_pembelian = PermintaanPembelian::with(['permintaanBahanWBB', 'details', 'pic'])->findOrFail($id);

        return view('pdf.purchasing.pdfPermintaanPembelian', compact('permintaan_pembelian'));
    }

    public function pdfIncomingMaterialSS($id)
    {
        $incomingSS = IncommingMaterialSS::with(['permintaanPembelian', 'summary', 'detail', 'pic'])->findOrFail($id);

        return view('pdf.quality.pdfIncomingMaterialSS', compact('incomingSS'));
    }

    public function pdfIncomingMaterialNonSS($id)
    {
        $incomingNonSS = IncommingMaterialNonSS::with(['permintaanPembelian', 'summary', 'detail', 'pic'])->findOrFail($id);

        return view('pdf.quality.pdfIncomingMaterialNonSS', compact('incomingNonSS'));
    }

    public function pdfIncomingMaterial($id)
    {
        $incomingMaterial = IncommingMaterial::with(['permintaanPembelian', 'details', 'pic'])->findOrFail($id);

        return view('pdf.warehouse.pdfIncomingMaterial', compact('incomingMaterial'));
    }

    public function downloadIncomingMaterial($id)
    {
        $incomingMaterial = IncommingMaterial::findOrFail($id);

        $filePath = $incomingMaterial->file_upload;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            // Respon JSON kalau file tidak ditemukan
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->download(storage_path('app/public/' . $filePath));

        // $fullPath = storage_path('app/public/' . $filePath);

        // return response()->download($fullPath);
    }

    public function pdfSerahTerima($id)
    {
        $serah_terima = SerahTerimaBahan::with(['permintaanBahanPro', 'details', 'pic'])->findOrFail($id);

        return view('pdf.warehouse.pdfSerahTerima', compact('serah_terima'));
    }

    public function pdfStandarisasiDrawing($id)
    {
        $standarisasi = StandarisasiDrawing::with(['spk', 'identitas', 'detail', 'pic'])->findOrFail($id);

        return view('pdf.quality.pdfStandarisasiDrawing', compact('standarisasi'));
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

            // Tambahkan hanya gambar dari gambar_lain
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
        $kelengkapan = KelengkapanMaterialSS::with(['spk', 'pic', 'detail'])->findOrFail($id);

        return view('pdf.quality.pdfKelengkapanMaterialSS', compact('kelengkapan'));
    }

    public function pdfPengecekanMaterialSS($id)
    {
        $pengecekanSS = PengecekanMaterialSS::with(['spk', 'pic', 'detail', 'penyerahan'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanMaterialSS', compact('pengecekanSS'));
    }

    public function pdfPenyerahanElectrical($id)
    {
        $serahElectrical = PenyerahanElectrical::with(['pengecekanSS', 'sebelumSerahTerima', 'pic', 'penerimaElectrical'])->findOrFail($id);

        return view('pdf.production.pdfPenyerahanElectrical', compact('serahElectrical'));
    }

    public function downloadPenyerahanElectrical($id)
    {
        $spesifikasi = PenyerahanElectrical::with(['sebelumSerahTerima'])->findOrFail($id);

        $filePath = $spesifikasi->sebelumSerahTerima->file_pendukung;

        $fullPath = storage_path('app/public/' . $filePath);

        return response()->download($fullPath);
    }

    public function pdfSPKQuality($id)
    {
        $spk_qc = SPKQuality::with(['spk', 'details', 'pic'])->findOrFail($id);

        return view('pdf.production.pdfSPKQuality', compact('spk_qc'));
    }

    public function pdfPengecekanElectrical($id)
    {
        $electrical = PengecekanMaterialElectrical::with(['spk', 'pic', 'detail'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanElectrical', compact('electrical'));
    }

    public function pdfPenyerahanProdukJadi($id)
    {
        $produkJadi = PenyerahanProdukJadi::with(['spk', 'details', 'pic'])->findOrFail($id);

        return view('pdf.production.pdfPenyerahanProdukJadi', compact('produkJadi'));
    }

    public function pdfPengecekanPerforma($id)
    {
        $performa = PengecekanPerforma::with(['spk', 'pic', 'detail'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanPerforma', compact('performa'));
    }

    public function pdfPelabelanQCPassed($id)
    {
        $pelabelan = QCPassed::with(['spk', 'pic', 'details'])->findOrFail($id);

        return view('pdf.warehouse.pdfPelabelanQCPassed', compact('pelabelan'));
    }

    public function pdfPeminjamanAlat($id)
    {
        $peminjaman = PeminjamanAlat::with(['details', 'pic'])->findOrFail($id);

        return view('pdf.warehouse.pdfPeminjamanAlat', compact('peminjaman'));
    }

    public function pdfDefectStatus($id)
    {
        $defect = DefectStatus::with(['spk', 'details', 'pic'])->findOrFail($id);

        return view('pdf.quality.pdfDefectStatus', compact('defect'));
    }

    public function pdfSPKVendor($id)
    {
        $vendor = SPKVendor::findOrFail($id);

        return view('pdf.production.pdfSPKVendor', compact('vendor'));
    }

    public function pdfSPKService()
    {
        return view('pdf.engineering.pdfSPKService');
    }

    public function pdfSparepartAlatKerja()
    {
        return view('pdf.engineering.pdfSparepartAlatKerja');
    }

    public function pdfBeritaAcara()
    {
        return view('pdf.engineering.pdfBeritaAcara');
    }
}
