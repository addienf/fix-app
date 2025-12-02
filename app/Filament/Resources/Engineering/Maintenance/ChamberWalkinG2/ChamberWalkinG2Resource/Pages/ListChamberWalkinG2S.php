<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListChamberWalkinG2S extends ListRecords
{
    protected static string $resource = ChamberWalkinG2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Walk-in Chamber G2'),
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
