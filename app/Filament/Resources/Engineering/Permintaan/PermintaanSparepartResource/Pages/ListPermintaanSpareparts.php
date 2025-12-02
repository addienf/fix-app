<?php

namespace App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\Pages;

use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPermintaanSpareparts extends ListRecords
{
    protected static string $resource = PermintaanSparepartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Permintaan Spareparts'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Diserahkan' => Tab::make()->query(fn($query) => $query->where('status_penyerahan', 'Diserahkan')),
                'Diketahui' => Tab::make()->query(fn($query) => $query->where('status_penyerahan', 'Diketahui')),
                'Belum Diketahui' => Tab::make()->query(fn($query) => $query->where('status_penyerahan', 'Belum Diketahui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
