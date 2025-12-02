<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListIncommingMaterialSS extends ListRecords
{
    protected static string $resource = IncommingMaterialSSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Incoming Material Stainless Steel'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Diterima' => Tab::make()->query(fn($query) => $query->where('status_penyelesaian', 'Diterima')),
                'Belum Diterima' => Tab::make()->query(fn($query) => $query->where('status_penyelesaian', 'Belum Diterima')),
                'Disetujui' => Tab::make()->query(fn($query) => $query->where('status_penyelesaian', 'Disetujui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
