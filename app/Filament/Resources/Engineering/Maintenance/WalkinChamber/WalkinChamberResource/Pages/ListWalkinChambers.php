<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListWalkinChambers extends ListRecords
{
    protected static string $resource = WalkinChamberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Walk-in Chamber'),
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
