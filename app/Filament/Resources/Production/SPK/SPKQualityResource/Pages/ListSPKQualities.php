<?php

namespace App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;

use App\Filament\Resources\Production\SPK\SPKQualityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListSPKQualities extends ListRecords
{
    protected static string $resource = SPKQualityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data SPK QC'),
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
