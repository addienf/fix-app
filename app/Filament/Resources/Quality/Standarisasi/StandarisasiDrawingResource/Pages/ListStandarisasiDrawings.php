<?php

namespace App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListStandarisasiDrawings extends ListRecords
{
    protected static string $resource = StandarisasiDrawingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Standarisasi Gambar'),
        ];
    }
    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Diperiksa' => Tab::make()->query(fn($query) => $query->where('status_pemeriksaan', 'Diperiksa')),
                'Belum Diperiksa' => Tab::make()->query(fn($query) => $query->where('status_pemeriksaan', 'Belum Diperiksa')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
