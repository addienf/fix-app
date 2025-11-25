<?php

namespace App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource\Pages;

use App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermintaanPelayananPelanggans extends ListRecords
{
    protected static string $resource = PermintaanPelayananPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Permintaan Pelayanan Pelanggan'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
