<?php

namespace App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource\Pages;

use App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermintaanPelayananPelanggan extends CreateRecord
{
    protected static string $resource = PermintaanPelayananPelangganResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Permintaan Pelayanan Pelanggan';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
