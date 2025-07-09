<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListQCPasseds extends ListRecords
{
    protected static string $resource = QCPassedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Pelabelan QC'),
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
