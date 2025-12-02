<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use Filament\Resources\Pages\Page;

class pdfPenyerahanProdukJadi extends Page
{
    protected static string $resource = PenyerahanProdukJadiResource::class;
    protected static ?string $title = 'PDF Penyerahan Produk Jadi';
    protected static string $view = 'filament.resources.production.penyerahan.penyerahan-produk-jadi-resource.pages.pdf-penyerahan-produk-jadi';

    public $record;

    public $produkJadi;

    public function mount($record)
    {
        $this->record = $record;
        $this->produkJadi = PenyerahanProdukJadi::with(['spk', 'details', 'pic'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
