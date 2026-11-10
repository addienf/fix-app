<?php

namespace App\Http\Controllers;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\Release\ProductRelease;
use Illuminate\Support\Facades\Storage;
use App\Traits\SimpleFormResource;

class PDFController extends Controller
{
    //
    use SimpleFormResource;

    public function pdfPermintaanAlatBahan($id)
    {
        $permintaan_alat_bahan = PermintaanAlatDanBahan::with(['jadwalProduksi', 'details', 'pic', 'pic.dibuatName', 'pic.diketahuiName', 'pic.diserahkanName'])->findOrFail($id);

        return $this->viewPdf('pdf.production.pdfPermintaanAlatBahan', compact('permintaan_alat_bahan'));
    }

    public function pdfIncomingMaterialSS($id)
    {
        $incomingSS = IncommingMaterialSS::with(['permintaanPembelian', 'summary', 'detail', 'pic', 'pic.checkedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfIncomingMaterialSS', compact('incomingSS'));
    }

    public function pdfIncomingMaterialNonSS($id)
    {
        $incomingNonSS = IncommingMaterialNonSS::with(['permintaanPembelian', 'summary', 'detail', 'pic', 'pic.checkedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfIncomingMaterialNonSS', compact('incomingNonSS'));
    }


    public function pdfPengecekanElectrical($id)
    {
        $electrical = PengecekanMaterialElectrical::with(['spkQC', 'pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfPengecekanElectrical', compact('electrical'));
    }

    public function pdfPengecekanPerforma($id)
    {
        $performa = PengecekanPerforma::with(['pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfPengecekanPerforma', compact('performa'));
    }

    public function pdfDefectStatus($id)
    {
        $defect = DefectStatus::with(['details', 'pic', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfDefectStatus', compact('defect'));
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

        return $this->viewPdf('pdf.quality.pdfKetidaksesuaian', compact('ketidaksesuaian'));
    }

    public function pdfProductRelease($id)
    {
        $release = ProductRelease::with(['pic', 'pic.dibuatName', 'pic.dikonfirmasiName', 'pic.diterimaName', 'pic.diketahuiName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfProductRelease', compact('release'));
    }
}
