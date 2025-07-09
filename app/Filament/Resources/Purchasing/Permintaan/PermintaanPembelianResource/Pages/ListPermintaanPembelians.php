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
                'Disetujui' => Tab::make()->query(fn($query) => $query->where('status_persetujuan', 'Disetujui')),
                'Belum Disetujui' => Tab::make()->query(fn($query) => $query->where('status_persetujuan', 'Belum Disetujui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
