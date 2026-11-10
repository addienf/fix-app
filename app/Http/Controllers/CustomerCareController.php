<?php

namespace App\Http\Controllers;

use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\GeneratesPdf;
use Carbon\Carbon;

class CustomerCareController extends Controller
{
    use GeneratesPdf;

    //
    public function pdfCatatanPelanggan(?int $id)
    {
        $complaint = Complain::with(['details', 'pic', 'pic.reportedBy', 'companies'])->findOrFail($id);
        $tanggal = Carbon::parse($complaint->tanggal)->format('d-m-Y');
        $filename = $complaint->form_no . ' - ' . $tanggal . '.pdf';
        return $this->streamPdf(
            view: 'pdf.engineering.pdfCatatanPelanggan',
            data: ['complaint' => $complaint],
            filename: $filename
        );
    }

    public function pdfPelayananPelanggan($id)
    {
        $pelayanan = PermintaanPelayananPelanggan::with(['pic', 'details', 'pic.diketahuiName', 'pic.diterimaName', 'pic.dibuatName'])->findOrFail($id);
        $filename = $pelayanan->no_form . '-' . now()->format('d-m-Y') . '.pdf';

        return $this->streamPdf(
            view: 'pdf.engineering.pdfPelayananPelanggan',
            data: ['pelayanan' => $pelayanan],
            filename: $filename
        );
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

        return $this->streamPdf(
            view: 'pdf.engineering.pdfSPKService',
            data: ['service' => $service],
            filename: $filename
        );
    }
}
