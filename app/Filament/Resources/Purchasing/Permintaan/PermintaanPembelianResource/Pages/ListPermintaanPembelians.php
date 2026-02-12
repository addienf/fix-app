<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPermintaanPembelians extends ListRecords
{
    protected static string $resource = PermintaanPembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Permintaan Pembelian'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Diketahui' => Tab::make()->query(fn($query) => $query->where('status_persetujuan', 'Diketahui')),
                'Belum Diketahui' => Tab::make()->query(fn($query) => $query->where('status_persetujuan', 'Belum Diketahui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
