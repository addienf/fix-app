<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListIncommingMaterialNonSS extends ListRecords
{
    protected static string $resource = IncommingMaterialNonSSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Incoming Material Non Stainless Steel'),
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
