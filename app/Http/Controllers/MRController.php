<?php

namespace App\Http\Controllers;

use App\Models\MR\Perubahan\PerubahanInformasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MRController extends Controller
{
    //
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

    public function pdfPerubahanInformasi($id)
    {
        $perubahan = PerubahanInformasi::with(['dokPerubahan', 'persetujuanPerubahan'])->findOrFail($id);
        $logoBase64 = $this->getBase64Logo();

        $pdf = Pdf::loadView('pdf.mr.pdfPerubahanInformasi', compact('perubahan', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('perubahan-informasi.pdf');
    }
}
