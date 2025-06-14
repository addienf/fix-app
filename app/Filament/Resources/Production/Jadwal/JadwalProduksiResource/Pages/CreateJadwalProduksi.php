<?php

namespace App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalProduksi extends CreateRecord
{
    protected static string $resource = JadwalProduksiResource::class;

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
