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

    public function pdfPermintaanAlatBahan(?int $id)
    {
        $permintaan_alat_bahan = PermintaanAlatDanBahan::with(['jadwalProduksi', 'details', 'pic', 'pic.dibuatName', 'pic.diketahuiName', 'pic.diserahkanName'])->findOrFail($id);

        return $this->viewPdf('pdf.production.pdfPermintaanAlatBahan', compact('permintaan_alat_bahan'));
    }

    public function pdfIncomingMaterialSS(?int $id)
    {
        $incomingSS = IncommingMaterialSS::with(['permintaanPembelian', 'summary', 'detail', 'pic', 'pic.checkedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfIncomingMaterialSS', compact('incomingSS'));
    }

    public function pdfIncomingMaterialNonSS(?int $id)
    {
        $incomingNonSS = IncommingMaterialNonSS::with(['permintaanPembelian', 'summary', 'detail', 'pic', 'pic.checkedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfIncomingMaterialNonSS', compact('incomingNonSS'));
    }


    public function pdfPengecekanElectrical(?int $id)
    {
        $electrical = PengecekanMaterialElectrical::with(['spkQC', 'pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfPengecekanElectrical', compact('electrical'));
    }

    public function pdfPengecekanPerforma(?int $id)
    {
        $performa = PengecekanPerforma::with(['pic', 'detail', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfPengecekanPerforma', compact('performa'));
    }

    public function pdfDefectStatus(?int $id)
    {
        $defect = DefectStatus::with(['details', 'pic', 'pic.inspectedName', 'pic.acceptedName', 'pic.approvedName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfDefectStatus', compact('defect'));
    }

    public function downloadDefectStatus(?int $id)
    {
        $defectStatus = DefectStatus::findOrFail($id);
        $filePath = $defectStatus->file_upload;

        return $this->downloadAttachment($defectStatus, $filePath);
    }

    public function pdfKetidaksesuaian(?int $id)
    {
        $ketidaksesuaian = Ketidaksesuaian::with(['pengecekanPerforma', 'pic', 'details', 'snk', 'pic.pelaporName', 'pic.diterimaName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfKetidaksesuaian', compact('ketidaksesuaian'));
    }

    public function pdfProductRelease(?int $id)
    {
        $release = ProductRelease::with(['pic', 'pic.dibuatName', 'pic.dikonfirmasiName', 'pic.diterimaName', 'pic.diketahuiName'])->findOrFail($id);

        return $this->viewPdf('pdf.quality.pdfProductRelease', compact('release'));
    }
}
