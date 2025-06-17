<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermintaanPembelians extends ListRecords
{
    protected static string $resource = PermintaanPembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Permintaan Pembelian'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
