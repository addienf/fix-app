<?php

namespace App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Filament\Resources\Pages\Page;

class pdfStandarisasiDrawing extends Page
{
    protected static string $resource = StandarisasiDrawingResource::class;

    protected static string $view = 'filament.resources.quality.standarisasi.standarisasi-drawing-resource.pages.pdf-standarisasi-drawing';

    protected static ?string $title = 'Standarisasi Gambar Kerja';

    public $record;

    public $standarisasi;

    public function mount($record)
    {
        $this->record = $record;
        $this->standarisasi = StandarisasiDrawing::with(['spk', 'identitas', 'detail', 'pic'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
