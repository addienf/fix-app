<?php

namespace App\Http\Controllers;

use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CustomerCareController extends Controller
{
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

    //
    public function pdfCatatanPelanggan($id)
    {
        $complaint = Complain::with(['details', 'pic', 'pic.reportedBy', 'companies'])->findOrFail($id);

        $tanggal = Carbon::parse($complaint->tanggal)->format('d-m-Y');
        $filename = $complaint->form_no . ' - ' . $tanggal . '.pdf';

        // $pdf = Pdf::loadView('pdf.engineering.pdfCatatanPelanggan', compact('complaint'))->setPaper('a4', 'portrait');

        $pdf = Pdf::loadView(
            'pdf.engineering.pdfCatatanPelanggan',
            [
                'complaint'   => $complaint,
                'logoBase64'  => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }

    public function pdfPelayananPelanggan($id)
    {
        $pelayanan = PermintaanPelayananPelanggan::with(['pic', 'details', 'pic.diketahuiName', 'pic.diterimaName', 'pic.dibuatName'])->findOrFail($id);

        $filename = $pelayanan->no_form . '-' . now()->format('d-m-Y') . '.pdf';

        // return Pdf::loadView('pdf.engineering.pdfPelayananPelanggan', compact('pelayanan'))->setPaper('a4', 'portrait')->stream($filename);
        return Pdf::loadView(
            'pdf.engineering.pdfPelayananPelanggan',
            [
                'pelayanan'  => $pelayanan,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait')
            ->stream($filename);
    }

    public function pdfSPKService($id)
    {
        $service = SPKService::with([
            'petugas',
            'details',
            'pic',
            'pic.dikonfirmasiNama',
            'pic.dibuatNama'
        ])->findOrFail($id);

        $tanggal = Carbon::parse($service->tanggal)->format('d-m-Y');

        $rawName = $service->no_spk_service . ' - ' . $tanggal;
        $filename = preg_replace('/[\/\\\\]/', '-', $rawName) . '.pdf';

        // $pdf = Pdf::loadView('pdf.engineering.pdfSPKService', compact('service'))
        //     ->setPaper('a4', 'portrait');
        $pdf = Pdf::loadView(
            'pdf.engineering.pdfSPKService',
            [
                'service'    => $service,
                'logoBase64' => $this->getBase64Logo(),
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }

    // public function pdfSPKService($id)
    // {
    //     $service = SPKService::with(['pelayananPelanggan', 'petugas', 'details', 'pic', 'pic.dikonfirmasiNama', 'pic.dibuatNama'])->findOrFail($id);

    //     $tanggal = Carbon::parse($service->tanggal)->format('d-m-Y');
    //     $filename = $service->no_spk_service . ' - ' . $tanggal . '.pdf';

    //     $pdf = Pdf::loadView('pdf.engineering.pdfSPKService', compact('service'))->setPaper('a4', 'portrait');

    //     return $pdf->stream($filename);
    // }

}
