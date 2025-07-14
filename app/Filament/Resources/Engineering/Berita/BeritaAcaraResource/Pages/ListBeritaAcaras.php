<?php

namespace App\Filament\Resources\Engineering\Berita\BeritaAcaraResource\Pages;

use App\Filament\Resources\Engineering\Berita\BeritaAcaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBeritaAcaras extends ListRecords
{
    protected static string $resource = BeritaAcaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Berita Acara'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
