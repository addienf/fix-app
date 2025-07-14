<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListChamberG2S extends ListRecords
{
    protected static string $resource = ChamberG2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Chamber G2'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Disetujui' => Tab::make()->query(fn($query) => $query->where('status_penyetujuan', 'Disetujui')),
                'Belum Disetujui' => Tab::make()->query(fn($query) => $query->where('status_penyetujuan', 'Belum Disetujui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
