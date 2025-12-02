<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use Filament\Resources\Pages\Page;

class pdfKelengkapanMaterialSS extends Page
{
    protected static string $resource = KelengkapanMaterialSSResource::class;

    protected static string $view = 'filament.resources.quality.kelengkapan-material.s-s.kelengkapan-material-s-s-resource.pages.pdf-kelengkapan-material-s-s';

    protected static ?string $title = 'Kelengkapan Material Stainless Steel';

    public $record;

    public $kelengkapan;

    public function mount($record)
    {
        $this->record = $record;
        $this->kelengkapan = KelengkapanMaterialSS::with(['spk', 'pic', 'detail'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
