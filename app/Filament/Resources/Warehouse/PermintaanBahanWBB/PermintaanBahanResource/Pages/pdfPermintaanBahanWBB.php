<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use Filament\Resources\Pages\Page;

class pdfPermintaanBahanWBB extends Page
{
    protected static string $resource = PermintaanBahanResource::class;

    protected static string $view = 'filament.resources.warehouse.permintaan-bahan-w-b-b.permintaan-bahan-resource.pages.pdf-permintaan-bahan-w-b-b';

    protected static ?string $title = 'Permintaan Bahan Warehouse';

    public $record;

    public $permintaan_bahan;

    public function mount($record)
    {
        $this->record = $record;
        $this->permintaan_bahan = PermintaanBahan::with(['permintaanBahanPro', 'details', 'pic'])->find($record);
    }
}
