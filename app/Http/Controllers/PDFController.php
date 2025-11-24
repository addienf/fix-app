<?php

namespace App\Http\Controllers;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\Service\ServiceReport;
use App\Models\Engineering\SPK\SPKService;
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
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Release\ProductRelease;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
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
        $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'pic', 'details.product', 'details.file', 'pic.signedName', 'pic.acceptedName', 'pic.acknowledgeName'])->findOrFail($id);

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
        $spk_mkt = SPKMarketing::with(['spesifikasiProduct', 'pic', 'pic.createName', 'pic.receiveName'])->findOrFail($id);

        return view('pdf.sales.pdfSPKMarketing', compact('spk_mkt'));
    }

    public function pdfJadwalProduksi($id)
    {
        $jadwal = JadwalProduksi::with(['spk', 'details', 'pic', 'sumbers', 'identifikasiProduks', 'timelines', 'pic.createName', 'pic.approveName'])->findOrFail($id);

        return view('pdf.production.pdfJadwalProduksi', compact('jadwal'));
    }

    public function downloadJadwalProduksi($id)
    {
        $jadwalProduksi = JadwalProduksi::findOrFail($id);

        $filePath = $jadwalProduksi->file_upload;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function pdfPermintaanAlatBahan($id)
    {
        $permintaan_alat_bahan = PermintaanAlatDanBahan::with(['jadwalProduksi', 'details', 'pic', 'pic.dibuatName', 'pic.diketahuiName', 'pic.diserahkanName'])->findOrFail($id);

        return view('pdf.production.pdfPermintaanAlatBahan', compact('permintaan_alat_bahan'));
    }

    public function pdfPermintaanBahan($id)
    {
        $permintaan_bahan = PermintaanBahan::with(['permintaanBahanPro', 'details', 'pic', 'pic.dibuatName', 'pic.mengetahuiName', 'pic.diserahkanName'])->findOrFail($id);

        return view('pdf.warehouse.pdfPermintaanBahan', compact('permintaan_bahan'));
    }

    public function pdfPermintaanPembelian($id)
    {
        $permintaan_pembelian = PermintaanPembelian::with(['permintaanBahanWBB', 'details', 'pic', 'pic.createName', 'pic.knowingName'])->findOrFail($id);

        return view('pdf.purchasing.pdfPermintaanPembelian', compact('permintaan_pembelian'));
    }

    public function pdfIncomingMaterialSS($id)
    {
        $incomingSS = IncommingMaterialSS::with(['permintaanPembelian', 'summary', 'detail', 'pic', 'pic.checkedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.quality.pdfIncomingMaterialSS', compact('incomingSS'));
    }

    public function pdfIncomingMaterialNonSS($id)
    {
        $incomingNonSS = IncommingMaterialNonSS::with(['permintaanPembelian', 'summary', 'detail', 'pic', 'pic.checkedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.quality.pdfIncomingMaterialNonSS', compact('incomingNonSS'));
    }

    public function pdfIncomingMaterial($id)
    {
        $incomingMaterial = IncommingMaterial::with(['permintaanPembelian', 'details', 'pic', 'pic.submitedName', 'pic.receivedName'])->findOrFail($id);

        return view('pdf.warehouse.pdfIncomingMaterial', compact('incomingMaterial'));
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

    public function pdfSerahTerima($id)
    {
        $serah_terima = SerahTerimaBahan::with(['peminjamanAlat', 'standarisasiDrawing', 'details', 'pic', 'pic.submitName', 'pic.receiveName'])->findOrFail($id);

        return view('pdf.warehouse.pdfSerahTerima', compact('serah_terima'));
    }

    public function pdfStandarisasiDrawing($id)
    {
        $standarisasi = StandarisasiDrawing::with(['serahTerimaWarehouse', 'identitas', 'detail', 'pemeriksaan', 'pic', 'pic.createName', 'pic.checkName'])->findOrFail($id);

        $no_spk = optional(
            $standarisasi->serahTerimaWarehouse
                ?->peminjamanAlat
                ?->spkVendor
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
                ?->peminjamanAlat
                ?->spkVendor
                ?->permintaanBahanProduksi
                ?->jadwalProduksi
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
                ?->peminjamanAlat
                ?->spkVendor
                ?->permintaanBahanProduksi
                ?->jadwalProduksi
                ?->spk
        )->no_spk ?? '-';

        return view('pdf.quality.pdfPengecekanMaterialSS', compact('pengecekanSS', 'no_spk'));
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

    public function pdfSPKQuality($id)
    {
        $spk_qc = SPKQuality::with(['penyerahanElectrical', 'details', 'pic', 'pic.createName', 'pic.receiveName'])->findOrFail($id);

        // $no_spk_mkt = $spk_qc->penyerahanElectrical->no_spk;

        return view('pdf.production.pdfSPKQuality', compact('spk_qc'));
    }

    public function pdfPengecekanElectrical($id)
    {
        $electrical = PengecekanMaterialElectrical::with(['penyerahanElectrical', 'pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanElectrical', compact('electrical'));
    }

    public function pdfPenyerahanProdukJadi($id)
    {
        $produkJadi = PenyerahanProdukJadi::with(['details', 'pic', 'pic.submitName', 'pic.receiveName'])->findOrFail($id);

        return view('pdf.production.pdfPenyerahanProdukJadi', compact('produkJadi'));
    }

    public function pdfPengecekanPerforma($id)
    {
        $performa = PengecekanPerforma::with(['pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanPerforma', compact('performa'));
    }

    public function pdfPelabelanQCPassed($id)
    {
        $pelabelan = QCPassed::with(['pengecekanPerforma', 'pic', 'details', 'pic.createdName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.warehouse.pdfPelabelanQCPassed', compact('pelabelan'));
    }

    public function pdfPeminjamanAlat($id)
    {
        $peminjaman = PeminjamanAlat::with(['details', 'pic', 'pic.NamaPeminjam'])->findOrFail($id);

        return view('pdf.warehouse.pdfPeminjamanAlat', compact('peminjaman'));
    }

    public function pdfDefectStatus($id)
    {
        $defect = DefectStatus::with(['details', 'pic', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.quality.pdfDefectStatus', compact('defect'));
    }

    public function downloadDefectStatus($id)
    {
        $defectStatus = DefectStatus::findOrFail($id);

        $filePath = $defectStatus->file_upload;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function pdfSPKVendor($id)
    {
        $vendor = SPKVendor::with(['permintaanBahanProduksi'])->findOrFail($id);

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
            foreach ($vendor->lampiran ?? [] as $gambarPath) {
                $fullGambarPath = storage_path('app/public/' . $gambarPath);
                if (file_exists($fullGambarPath)) {
                    $zip->addFile($fullGambarPath, 'gambar/' . basename($gambarPath));
                }
            }

            $zip->close();
        }

        // Kirimkan file ZIP sebagai download dan hapus setelah dikirim
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function pdfSPKService($id)
    {
        $service = SPKService::with(['complain', 'petugas', 'pemeriksaanPersetujuan', 'pic', 'pic.dikonfirmasiNama', 'pic.diketahuiNama'])->findOrFail($id);

        return view('pdf.engineering.pdfSPKService', compact('service'));
    }

    public function pdfSparepartAlatKerja($id)
    {
        $sparepart = PermintaanSparepart::with(['spkService', 'details', 'pic', 'pic.dibuatName', 'pic.diketahuiName', 'pic.diserahkanName'])->findOrFail($id);

        return view('pdf.engineering.pdfSparepartAlatKerja', compact('sparepart'));
    }

    public function pdfBeritaAcara($id)
    {
        $berita = BeritaAcara::with(['spkService', 'detail', 'pic', 'pelanggan', 'penyediaJasa', 'pic.jasaName'])->findOrFail($id);

        return view('pdf.engineering.pdfBeritaAcara', compact('berita'));
    }

    public function pdfMaintenanceChamberG2($id)
    {
        $G2 = ChamberG2::with(['spkService', 'detail', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfMaintenanceChamberG2', compact('G2'));
    }
    public function pdfWalkInChamberG2($id)
    {
        $walkinG2 = ChamberWalkinG2::with(['spkService', 'detail', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfWalkInChamberG2', compact('walkinG2'));
    }

    public function pdfRissingPipette($id)
    {
        $rissing = RissingPipette::with(['spkService', 'detail', 'pic', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfRissingPipette', compact('rissing'));
    }

    public function pdfWalkInChamberR1($id)
    {
        $walkin = WalkinChamber::with(['spkService', 'detail', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfWalkInChamberR1', compact('walkin'));
    }

    public function pdfWalkInChamberR2($id)
    {
        $R2 = ChamberR2::with(['spkService', 'detail', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfWalkInChamberR2', compact('R2'));
    }
    public function pdfMaintenanceRefrigator($id)
    {
        $refrigerator = Refrigerator::with(['spkService', 'detail', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfMaintenanceRefrigator', compact('refrigerator'));
    }
    public function pdfMaintenanceColdRoom($id)
    {
        $cold = ColdRoom::with(['spkService', 'detail', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfMaintenanceColdRoom', compact('cold'));
    }

    public function pdfServiceReport($id)
    {
        $serviceReport = ServiceReport::with(['spkService', 'details', 'produkServices', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfServiceReport', compact('serviceReport'));
    }

    public function pdfCatatanPelanggan($id)
    {
        $complaint = Complain::with(['details', 'pic', 'pic.reportedBy'])->findOrFail($id);

        return view('pdf.engineering.pdfCatatanPelanggan', compact('complaint'));
    }

    public function downloadZipserviceReport($id)
    {
        $serviceReport = ServiceReport::with(['spkService', 'details', 'produkServices', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        $zipFileName = 'service-report' . $serviceReport->id . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

            foreach ($serviceReport->details as $detail) {
                foreach ($detail->upload_file ?? [] as $gambarPath) {
                    $fullPath = storage_path('app/public/' . $gambarPath);
                    if (file_exists($fullPath)) {
                        $zip->addFile($fullPath, basename($gambarPath));
                    }
                }
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function pdfKetidaksesuaian($id)
    {
        $ketidaksesuaian = Ketidaksesuaian::with(['pengecekanPerforma', 'pic', 'details', 'snk', 'pic.pelaporName', 'pic.diterimaName'])->findOrFail($id);

        return view('pdf.quality.pdfKetidaksesuaian', compact('ketidaksesuaian'));
    }

    public function pdfProductRelease($id)
    {
        $release = ProductRelease::with(['pic', 'pic.dibuatName', 'pic.dikonfirmasiName', 'pic.diterimaName', 'pic.diketahuiName'])->findOrFail($id);

        return view('pdf.quality.pdfProductRelease', compact('release'));
    }
}
