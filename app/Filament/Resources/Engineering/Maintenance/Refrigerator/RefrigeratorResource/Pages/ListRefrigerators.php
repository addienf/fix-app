<?php

namespace App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListRefrigerators extends ListRecords
{
    protected static string $resource = RefrigeratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Refrigerator'),
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
