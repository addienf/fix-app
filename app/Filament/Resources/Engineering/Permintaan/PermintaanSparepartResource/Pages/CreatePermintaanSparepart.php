<?php

namespace App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\Pages;

use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermintaanSparepart extends CreateRecord
{
    protected static string $resource = PermintaanSparepartResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Permintaan Spareparts';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
