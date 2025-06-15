<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQCPassed extends CreateRecord
{
    protected static string $resource = QCPassedResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Pelabelan QC Passed';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
