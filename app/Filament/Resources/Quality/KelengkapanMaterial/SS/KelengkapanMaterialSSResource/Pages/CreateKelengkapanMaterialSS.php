<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKelengkapanMaterialSS extends CreateRecord
{
    protected static string $resource = KelengkapanMaterialSSResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Kelengkapan Material';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
