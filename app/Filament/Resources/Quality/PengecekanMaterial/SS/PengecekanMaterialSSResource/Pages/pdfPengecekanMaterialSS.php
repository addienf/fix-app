<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use Filament\Resources\Pages\Page;


class pdfPengecekanMaterialSS extends Page
{
    protected static string $resource = PengecekanMaterialSSResource::class;

    protected static ?string $title = 'Pengecekan Material Stainless Steel';

    protected static string $view = 'filament.resources.quality.pengecekan-material.s-s.pengecekan-material-s-s-resource.pages.pdf-pengecekan-material-s-s';

    public $record;

    public $pengecekanSS;

    public function mount($record)
    {
        $this->record = $record;
        $this->pengecekanSS = PengecekanMaterialSS::with(['spk', 'pic', 'detail', 'penyerahan'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
