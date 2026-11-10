<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

trait GeneratesPdf
{
    // Logo Qlab
    protected function getBase64Logo(?string $path = null): ?string
    {
        $logoPath = $path ?? public_path('asset/logo.png');

        if (! file_exists($logoPath)) {
            return null;
        }

        $type = pathinfo($logoPath, PATHINFO_EXTENSION);

        return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    // Untuk PDF yang pake DomPDF
    protected function streamPdf(
        string $view,
        array $data,
        string $filename,
        string $paper = 'a4',
        string $orientation = 'portrait'
    ): Response {
        $getModel = reset($data);

        if ($getModel instanceof Model) {

            $this->authorize('view', $getModel);
        }

        $data['logoBase64'] = $this->getBase64Logo();

        return Pdf::loadView($view, $data)
            ->setPaper($paper, $orientation)
            ->stream($filename);
    }

    // Untuk Maintenance
    protected function renderWalkinPdf(
        string $modelClass,
        string $view,
        int $id,
        string $dataKey = 'walkin'
    ) {
        $data = $modelClass::with([
            'spkService',
            'detail',
            'pic',
            'pic.approvedBy',
            'pic.checkedBy',
        ])->findOrFail($id);

        $tagNo = str_replace(['/', ' '], '-', $data->tag_no);
        $date  = now()->format('Ymd');
        $filename = "MT-{$tagNo}-{$date}.pdf";

        return $this->streamPdf(
            view: $view,
            data: [$dataKey => $data],
            filename: $filename
        );
    }

    // Untuk html2pdf
    protected function viewPdf(string $view, array $data): \Illuminate\Contracts\View\View
    {
        $getModel = reset($data);
        if ($getModel instanceof Model) {
            $this->authorize('view', $getModel);
        }
        return view($view, $data);
    }

    // Download Attachment
    protected function downloadAttachment(Model $model, ?string $filePath): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->authorize('view', $model);
        abort_if(blank($filePath), 404, 'File belum diunggah.');
        abort_unless(\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath), 404, 'File tidak ditemukan.');
        return response()->download(\Illuminate\Support\Facades\Storage::disk('public')->path($filePath));
    }
}
