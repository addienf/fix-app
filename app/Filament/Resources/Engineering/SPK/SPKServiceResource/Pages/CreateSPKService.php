<?php

namespace App\Filament\Resources\Engineering\SPK\SPKServiceResource\Pages;

use App\Filament\Resources\Engineering\SPK\SPKServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSPKService extends CreateRecord
{
    protected static string $resource = SPKServiceResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data SPK Service';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
