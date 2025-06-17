<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermintaanPembelian extends CreateRecord
{
    protected static string $resource = PermintaanPembelianResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Jadwal Produksi';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
