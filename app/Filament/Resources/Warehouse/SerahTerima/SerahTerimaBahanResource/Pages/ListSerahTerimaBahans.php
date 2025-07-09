<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListSerahTerimaBahans extends ListRecords
{
    protected static string $resource = SerahTerimaBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Serah Terima Bahan'),
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
