<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use Filament\Resources\Pages\Page;

class pdfIncommingMaterialNonSS extends Page
{
    protected static string $resource = IncommingMaterialNonSSResource::class;

    protected static ?string $title = 'Incoming Material Non Stainless Steel';

    protected static string $view = 'filament.resources.quality.incomming-material.material-non-s-s.incomming-material-non-s-s-resource.pages.pdf-incomming-material-non-s-s';

    public $record;

    public $incomingNonSS;

    public function mount($record)
    {
        $this->record = $record;
        $this->incomingNonSS = IncommingMaterialNonSS::with(['permintaanPembelian', 'summary', 'detail', 'pic'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
