<?php

namespace App\Filament\Resources\Production\SPKVendor\SPKVendorResource\Pages;

use App\Filament\Resources\Production\SPKVendor\SPKVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPKVendors extends ListRecords
{
    protected static string $resource = SPKVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data SPK Vendor'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
