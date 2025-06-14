<?php

namespace App\Filament\Resources\Sales\SPK\SPKResource\Pages;

use App\Filament\Resources\Sales\SPK\SPKResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSPK extends CreateRecord
{
    protected static string $resource = SPKResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data SPK Marketing';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
