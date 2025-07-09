<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPengecekanElectricals extends ListRecords
{
    protected static string $resource = PengecekanElectricalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Pengecekan Material Electrical'),
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
