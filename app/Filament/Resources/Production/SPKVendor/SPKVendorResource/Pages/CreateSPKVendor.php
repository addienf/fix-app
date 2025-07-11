<?php

namespace App\Filament\Resources\Production\SPKVendor\SPKVendorResource\Pages;

use App\Filament\Resources\Production\SPKVendor\SPKVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSPKVendor extends CreateRecord
{
    protected static string $resource = SPKVendorResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data SPK Vendor';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
