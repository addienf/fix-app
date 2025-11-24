<?php

namespace App\Filament\Resources\Quality\Release\ProductReleaseResource\Pages;

use App\Filament\Resources\Quality\Release\ProductReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductRelease extends CreateRecord
{
    protected static string $resource = ProductReleaseResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Product Release';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
