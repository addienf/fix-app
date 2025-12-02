<?php

namespace App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use Filament\Resources\Pages\Page;

class pdfIncommingMaterial extends Page
{
    protected static string $resource = IncommingMaterialResource::class;
    protected static ?string $title = 'PDF Incomming Material';
    protected static string $view = 'filament.resources.warehouse.incomming.incomming-material-resource.pages.pdf-incomming-material';

    public $record;

    public $incomingMaterial;

    public function mount($record)
    {
        $this->record = $record;
        $this->incomingMaterial = IncommingMaterial::with(['permintaanPembelian', 'details', 'pic'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
