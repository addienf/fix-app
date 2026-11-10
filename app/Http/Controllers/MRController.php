<?php

namespace App\Http\Controllers;

use App\Models\MR\Perubahan\PerubahanInformasi;

class MRController extends Controller
{
    //

    public function pdfPerubahanInformasi($id)
    {
        $perubahan = PerubahanInformasi::with(['dokPerubahan', 'persetujuanPerubahan'])->findOrFail($id);
        return $this->streamPdf(
            view: 'pdf.mr.pdfPerubahanInformasi',
            data: ['perubahan' => $perubahan],
            filename: 'perubahan-informasi.pdf'
        );
    }
}
