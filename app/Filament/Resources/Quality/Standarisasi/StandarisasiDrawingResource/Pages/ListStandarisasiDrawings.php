<?php

namespace App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStandarisasiDrawings extends ListRecords
{
    protected static string $resource = StandarisasiDrawingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Standarisasi Gambar'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
