<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermintaanBahans extends ListRecords
{
    protected static string $resource = PermintaanBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Permintaan Bahan'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
