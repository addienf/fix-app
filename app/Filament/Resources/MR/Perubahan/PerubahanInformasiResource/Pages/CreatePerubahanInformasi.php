<?php

namespace App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource\Pages;

use App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePerubahanInformasi extends CreateRecord
{
    protected static string $resource = PerubahanInformasiResource::class;

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
