<?php

namespace App\Http\Controllers;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Berita\Pivot\BeritaAcaraPIC;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\Service\ServiceReport;
use App\Traits\GeneratesPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Illuminate\Http\Request;

class EngineeringController extends Controller
{
    //
    use GeneratesPdf;

    public function pdfWalkInChamberG2(?int $id)
    {
        return $this->renderWalkinPdf(ChamberWalkinG2::class, 'pdf.engineering.pdfWalkInChamberG2', $id, 'walkinG2');
    }

    public function pdfWalkInChamber(?int $id)
    {
        return $this->renderWalkinPdf(WalkinChamber::class, 'pdf.engineering.pdfWalkInChamber', $id, 'walkin');
    }

    public function pdfStabilityChamber(?int $id)
    {
        return $this->renderWalkinPdf(ChamberR2::class, 'pdf.engineering.pdfStabilityChamber', $id, 'stabilityChamber');
    }

    public function pdfMaintenanceRefrigator(?int $id)
    {
        return $this->renderWalkinPdf(Refrigerator::class, 'pdf.engineering.pdfMaintenanceRefrigator', $id, 'refrigerator');
    }

    public function pdfMaintenanceColdRoom(?int $id)
    {
        return $this->renderWalkinPdf(ColdRoom::class, 'pdf.engineering.pdfMaintenanceColdRoom', $id, 'cold');
    }

    public function pdfMaintenanceChamberG2(?int $id)
    {
        return $this->renderWalkinPdf(ChamberG2::class, 'pdf.engineering.pdfMaintenanceChamberG2', $id, 'G2');
    }

    public function pdfRissingPipette(?int $id)
    {
        return $this->renderWalkinPdf(RissingPipette::class, 'pdf.engineering.pdfRissingPipette', $id, 'rissing');
    }

    public function pdfServiceReport(?int $id)
    {

        $serviceReport = ServiceReport::with(['spkService', 'details', 'produkServices', 'pic', 'pic.approvedBy', 'pic.checkedBy'])->findOrFail($id);
        $tanggal = \Carbon\Carbon::parse($serviceReport->tanggal)->format('d-m-Y');
        $formNo = str_replace(['/', '\\', ' '], '-', $serviceReport->form_no);
        $fileBaseName = $formNo . '-' . $tanggal;
        $this->authorize('view', $serviceReport);

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
        @unlink($pdfPath);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function pdfBeritaAcara(?int $id)
    {
        $berita = BeritaAcara::with(['spkService', 'detail', 'pic', 'pelanggan', 'penyediaJasa', 'pic.jasaName'])->findOrFail($id);
        $tanggal = \Carbon\Carbon::parse($berita->tanggal)->format('d-m-Y');
        $noSurat = str_replace(['/', '\\'], '-', $berita->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        return $this->streamPdf(
            view: 'pdf.engineering.pdfBeritaAcara',
            data: ['berita' => $berita],
            filename: $fileName
        );
    }

    public function pdfSparepartAlatKerja(?int $id)
    {
        $sparepart = PermintaanSparepart::with(['spkService', 'details', 'pic', 'pic.dibuatName', 'pic.diketahuiName', 'pic.diserahkanName'])->findOrFail($id);
        $tanggal = \Carbon\Carbon::parse($sparepart->tanggal)->format('d-m-Y');
        $noSurat = str_replace(['/', '\\'], '-', $sparepart->no_surat);
        $fileName = $noSurat . ' - ' . $tanggal . '.pdf';

        return $this->streamPdf(
            view: 'pdf.engineering.pdfSparepartAlatKerja',
            data: ['sparepart' => $sparepart],
            filename: $fileName
        );
    }

    protected function resolveModel(string $type)
    {
        $model = config("signature_models.$type");

        abort_if(!$model, 404);

        return $model;
    }

    public function show2(?string $type, ?string $token)
    {
        $model = $this->resolveModel($type);
        $logoBase64 = $this->getBase64Logo();
        $record = $model::findValidToken($token);

        if (! $record) {
            return response()->view('pdf.engineering.pdfExpiredLink', compact('logoBase64'), 403);
        }

        $config = $record->signatureConfig();
        $pic = $record;

        return view('pdf.engineering.pdfSignatureLink', compact('record', 'type', 'logoBase64', 'config', 'pic'));
    }

    public function store2(Request $request, ?string $type, ?string $token)
    {
        $model = $this->resolveModel($type);
        $logoBase64 = $this->getBase64Logo();
        $record = $model::findValidToken($token);

        if (!$record) {
            return response()->view('pdf.engineering.pdfExpiredLink', compact('logoBase64'), 403);
        }

        $config = $record->signatureConfig();

        $request->validate([
            $config['name_field'] => 'required|string|max:255',
            $config['signature_field'] => 'required',
        ]);

        $record->saveSignature($request);

        return view('pdf.engineering.pdfSignatureLinkSuccess', compact('logoBase64'));
    }

    public function previewSignaturePdf(?string $type, ?string $token)
    {
        $model = $this->resolveModel($type);
        $record = $model::findValidToken($token);
        abort_if(! $record, 403);
        $berita = BeritaAcara::with(['spkService', 'detail', 'pic', 'pelanggan', 'penyediaJasa', 'pic.jasaName'])
            ->findOrFail($record->berita_id);
        return Pdf::loadView('pdf.engineering.pdfBeritaAcara', [
            'berita' => $berita,
            'logoBase64' => $this->getBase64Logo(),
        ])->setPaper('a4', 'portrait')->stream('preview-berita-acara.pdf');
    }

    public function invitation(?string $type, ?string $token)
    {
        $pelayanan = PermintaanPelayananPelanggan::with('details', 'pic')->where('invitation_token', $token)->firstOrFail();
        $model = $this->resolveModel($type);
        $logoBase64 = $this->getBase64Logo();
        $record = $model::findValidToken($token);

        if (!$record) {
            return response()->view('pdf.engineering.pdfExpiredLink', compact('logoBase64'), 403);
        }

        $config = $record->signatureConfig();

        return view('pdf.engineering.pdfSignatureLink', compact('record', 'type', 'logoBase64', 'config', 'pelayanan'));
    }
}
