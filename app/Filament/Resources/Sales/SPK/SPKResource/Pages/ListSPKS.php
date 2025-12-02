<?php

namespace App\Filament\Resources\Sales\SPK\SPKResource\Pages;

use App\Filament\Resources\Sales\SPK\SPKResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListSPKS extends ListRecords
{
    protected static string $resource = SPKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data SPK'),
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
