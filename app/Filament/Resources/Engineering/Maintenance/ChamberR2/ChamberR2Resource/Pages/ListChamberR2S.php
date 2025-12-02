<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListChamberR2S extends ListRecords
{
    protected static string $resource = ChamberR2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Chamber R2'),
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
