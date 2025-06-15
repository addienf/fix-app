<?php

namespace App\Filament\Resources\General\URS\URSResource\Pages;

use App\Filament\Resources\General\URS\URSResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateURS extends CreateRecord
{
    protected static string $resource = URSResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Nomor URS';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
