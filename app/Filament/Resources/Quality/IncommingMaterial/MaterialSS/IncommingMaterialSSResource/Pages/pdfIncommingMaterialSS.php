<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use Filament\Resources\Pages\Page;

class pdfIncommingMaterialSS extends Page
{
    protected static string $resource = IncommingMaterialSSResource::class;

    protected static ?string $title = 'Incoming Material SS';

    protected static string $view = 'filament.resources.quality.incomming-material.material-s-s.incomming-material-s-s-resource.pages.pdf-incomming-material-s-s';

    public $record;

    public $incomingSS;

    public function mount($record)
    {
        $this->record = $record;
        $this->incomingSS = IncommingMaterialSS::with(['permintaanPembelian', 'summary', 'detail', 'pic'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
