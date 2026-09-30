<?php

namespace App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Pages;

use App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPenerimaanBarangs extends ListRecords
{
    protected static string $resource = PenerimaanBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Penerimaan Barang'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Diketahui' => Tab::make()->query(fn($query) => $query->where('status', 'Diketahui')),
                'Belum Diketahui' => Tab::make()->query(fn($query) => $query->where('status', 'Belum Diketahui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
