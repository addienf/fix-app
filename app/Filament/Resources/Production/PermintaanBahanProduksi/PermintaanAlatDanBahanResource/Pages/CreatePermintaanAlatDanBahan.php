<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermintaanAlatDanBahan extends CreateRecord
{
    protected static string $resource = PermintaanAlatDanBahanResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
