<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPenyerahanProdukJadis extends ListRecords
{
    protected static string $resource = PenyerahanProdukJadiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Penyerahan Produk Jadi'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Diterima' => Tab::make()->query(fn($query) => $query->where('status_penerimaan', 'Diterima')),
                'Belum Diterima' => Tab::make()->query(fn($query) => $query->where('status_penerimaan', 'Belum Diterima')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
