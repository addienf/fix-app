<?php

namespace App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStandarisasiDrawing extends CreateRecord
{
    protected static string $resource = StandarisasiDrawingResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Standarisasi Gambar Kerja';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
