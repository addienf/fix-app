<?php

namespace App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListIncommingMaterials extends ListRecords
{
    protected static string $resource = IncommingMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Incoming Material'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Diterima' => Tab::make()->query(fn($query) => $query->where('status_penerimaan_pic', 'Diterima')),
                'Belum Diterima' => Tab::make()->query(fn($query) => $query->where('status_penerimaan_pic', 'Belum Diterima')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
