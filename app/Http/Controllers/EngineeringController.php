<?php

namespace App\Http\Controllers;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\Service\ServiceReport;
use App\Traits\SimpleFormResource;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class EngineeringController extends Controller
{
    //
    use SimpleFormResource;

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

    public function pdfWalkInChamberG2($id)
    {
        return $this->renderWalkinPdf(ChamberWalkinG2::class, 'pdf.engineering.pdfWalkInChamberG2', $id, 'walkinG2');
    }

    public function pdfWalkInChamber($id)
    {
        return $this->renderWalkinPdf(WalkinChamber::class, 'pdf.engineering.pdfWalkInChamber', $id, 'walkin');
    }

    public function pdfStabilityChamber($id)
    {
        return $this->renderWalkinPdf(ChamberR2::class, 'pdf.engineering.pdfStabilityChamber', $id, 'stabilityChamber');
    }

    public function pdfMaintenanceRefrigator($id)
    {
        return $this->renderWalkinPdf(Refrigerator::class, 'pdf.engineering.pdfMaintenanceRefrigator', $id, 'refrigerator');
    }

    public function pdfMaintenanceColdRoom($id)
    {
        return $this->renderWalkinPdf(ColdRoom::class, 'pdf.engineering.pdfMaintenanceColdRoom', $id, 'cold');
    }

    public function pdfMaintenanceChamberG2($id)
    {
        return $this->renderWalkinPdf(ChamberG2::class, 'pdf.engineering.pdfMaintenanceChamberG2', $id, 'G2');
    }

    public function pdfRissingPipette($id)
    {
        return $this->renderWalkinPdf(RissingPipette::class, 'pdf.engineering.pdfRissingPipette', $id, 'rissing');
    }

    public function pdfServiceReport($id)
    {

        $serviceReport = ServiceReport::with(['spkService', 'details', 'produkServices', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);

        $tanggal = \Carbon\Carbon::parse($serviceReport->tanggal)->format('d-m-Y'); // contoh: 2026-01-14

        $formNo = str_replace(['/', '\\', ' '], '-', $serviceReport->form_no);

        $fileBaseName = $formNo . '-' . $tanggal;

        // $pdf = Pdf::loadView('pdf.engineering.pdfServiceReport', compact('serviceReport'))->setPaper('a4', 'portrait');

        $pdf = Pdf::loadView(
            'pdf.engineering.pdfServiceReport',
            [
                'serviceReport' => $serviceReport,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        $pdfName = $fileBaseName . '.pdf';
        $pdfPath = storage_path('app/temp/' . $pdfName);

        if (!file_exists(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }

        file_put_contents($pdfPath, $pdf->output());

        $zipName = $fileBaseName . '.zip';
        $zipPath = storage_path('app/temp/' . $zipName);

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addFile($pdfPath, $pdfName);

        foreach ($serviceReport->details as $detail) {
            foreach ($detail->upload_file ?? [] as $img) {
                $imgPath = storage_path('app/public/' . $img);
                if (file_exists($imgPath)) {
                    $zip->addFile($imgPath, 'attachments/' . basename($img));
                }
            }
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function pdfBeritaAcara($id)
    {
        $berita = BeritaAcara::with(['spkService', 'detail', 'pic', 'pelanggan', 'penyediaJasa', 'pic.jasaName'])->findOrFail($id);

        $tanggal = \Carbon\Carbon::parse($berita->tanggal)->format('d-m-Y');
        $noSurat = str_replace(['/', '\\'], '-', $berita->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        // $pdf = Pdf::loadView('pdf.engineering.pdfBeritaAcara', compact('berita'))->setPaper('a4', 'portrait');

        $pdf = Pdf::loadView(
            'pdf.engineering.pdfBeritaAcara',
            [
                'berita' => $berita,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function pdfSparepartAlatKerja($id)
    {
        $sparepart = PermintaanSparepart::with(['spkService', 'details', 'pic', 'pic.dibuatName', 'pic.diketahuiName', 'pic.diserahkanName'])->findOrFail($id);

        $tanggal = \Carbon\Carbon::parse($sparepart->tanggal)->format('d-m-Y');
        $noSurat = str_replace(['/', '\\'], '-', $sparepart->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        // $pdf = Pdf::loadView('pdf.engineering.pdfSparepartAlatKerja', compact('sparepart'))->setPaper('a4', 'portrait');

        $pdf = Pdf::loadView(
            'pdf.engineering.pdfSparepartAlatKerja',
            [
                'sparepart' => $sparepart,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }
}
