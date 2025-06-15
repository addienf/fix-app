<?php

namespace App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengecekanPerforma extends CreateRecord
{
    protected static string $resource = PengecekanPerformaResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Pengecekan Performa';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
