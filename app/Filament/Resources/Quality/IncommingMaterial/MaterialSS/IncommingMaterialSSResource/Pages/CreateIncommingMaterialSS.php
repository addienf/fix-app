<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncommingMaterialSS extends CreateRecord
{
    protected static string $resource = IncommingMaterialSSResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Incoming Material Stainless Steel';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
