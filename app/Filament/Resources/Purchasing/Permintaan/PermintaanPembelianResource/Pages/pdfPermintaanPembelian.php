<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use Filament\Resources\Pages\Page;

class pdfPermintaanPembelian extends Page
{
    protected static string $resource = PermintaanPembelianResource::class;

    protected static string $view = 'filament.resources.purchasing.permintaan.permintaan-pembelian-resource.pages.pdf-permintaan-pembelian';

    protected static ?string $title = 'Permintaan Pembelian';

    public $record;

    public $permintaan_pembelian;

    public function mount($record)
    {
        $this->record = $record;
        $this->permintaan_pembelian = PermintaanPembelian::with(['permintaanBahanWBB', 'details', 'pic'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
