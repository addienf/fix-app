<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use Filament\Resources\Pages\Page;

class pdfPermintaanAlatdanBahan extends Page
{
    protected static string $resource = PermintaanAlatDanBahanResource::class;

    protected static string $view = 'filament.resources.production.permintaan-bahan-produksi.permintaan-alat-dan-bahan-resource.pages.pdf-permintaan-alatdan-bahan';

    protected static ?string $title = 'Permintaan Alat dan Bahan';

    public $record;

    public $permintaan_alat_bahan;

    public function mount($record)
    {
        $this->record = $record;
        $this->permintaan_alat_bahan = PermintaanAlatDanBahan::with(['spk', 'details', 'pic'])->find($record);
    }
}
