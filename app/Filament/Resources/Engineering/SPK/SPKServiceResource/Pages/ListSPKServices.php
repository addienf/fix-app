<?php

namespace App\Filament\Resources\Engineering\SPK\SPKServiceResource\Pages;

use App\Filament\Resources\Engineering\SPK\SPKServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListSPKServices extends ListRecords
{
    protected static string $resource = SPKServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data SPK Service'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Selesai' => Tab::make()->query(fn($query) => $query->where('status_penyelesaian', 'Selesai')),
                'Belum Diselesaikan' => Tab::make()->query(fn($query) => $query->where('status_penyelesaian', 'Belum Diselesaikan')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
