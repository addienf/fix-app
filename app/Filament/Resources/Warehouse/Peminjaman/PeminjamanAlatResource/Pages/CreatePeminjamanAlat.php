<?php

namespace App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\Pages;

use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjamanAlat extends CreateRecord
{
    protected static string $resource = PeminjamanAlatResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Peminjaman Alat';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
