<?php

namespace App\Http\Controllers;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\Release\ProductRelease;
use App\Models\Warehouse\Pelabelan\QCPassed;
use Illuminate\Support\Facades\Storage;
use App\Traits\SimpleFormResource;

class PDFController extends Controller
{
    //
    use SimpleFormResource;

    public function pdfPermintaanAlatBahan($id)
    {
        $permintaan_alat_bahan = PermintaanAlatDanBahan::with(['jadwalProduksi', 'details', 'pic', 'pic.dibuatName', 'pic.diketahuiName', 'pic.diserahkanName'])->findOrFail($id);

        return view('pdf.production.pdfPermintaanAlatBahan', compact('permintaan_alat_bahan'));
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

    // public function pdfPenyerahanElectrical($id)
    // {
    //     $serahElectrical = PenyerahanElectrical::with(['pengecekanSS', 'sebelumSerahTerima', 'pic', 'penerimaElectrical', 'pic.submitName', 'pic.receiveName', 'pic.knowingName'])->findOrFail($id);

    //     return view('pdf.production.pdfPenyerahanElectrical', compact('serahElectrical'));
    // }

    // public function downloadPenyerahanElectrical($id)
    // {
    //     $spesifikasi = PenyerahanElectrical::with(['sebelumSerahTerima'])->findOrFail($id);

    //     $filePath = $spesifikasi->sebelumSerahTerima->file_pendukung;

    //     $fullPath = storage_path('app/public/' . $filePath);

    //     return response()->download($fullPath);
    // }

    // public function pdfSPKQuality($id)
    // {
    //     $spk_qc = SPKQuality::with(['penyerahanElectrical', 'details', 'pic', 'pic.createName', 'pic.receiveName'])->findOrFail($id);

    //     return view('pdf.production.pdfSPKQuality', compact('spk_qc'));
    // }

    public function pdfPengecekanElectrical($id)
    {
        $electrical = PengecekanMaterialElectrical::with(['penyerahanElectrical', 'pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanElectrical', compact('electrical'));
    }

    // public function pdfPenyerahanProdukJadi($id)
    // {
    //     $produkJadi = PenyerahanProdukJadi::with(['details', 'pic', 'pic.submitName', 'pic.receiveName'])->findOrFail($id);

    //     return view('pdf.production.pdfPenyerahanProdukJadi', compact('produkJadi'));
    // }

    public function pdfPengecekanPerforma($id)
    {
        $performa = PengecekanPerforma::with(['pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanPerforma', compact('performa'));
    }

    // public function pdfPelabelanQCPassed($id)
    // {
    //     $pelabelan = QCPassed::with(['pic', 'details', 'pic.createdName', 'pic.approvedName', 'productRelease'])->findOrFail($id);

    //     return view('pdf.warehouse.pdfPelabelanQCPassed', compact('pelabelan'));
    // }

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
