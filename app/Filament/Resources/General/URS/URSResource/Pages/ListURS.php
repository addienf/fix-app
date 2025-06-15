<?php

namespace App\Filament\Resources\General\URS\URSResource\Pages;

use App\Filament\Resources\General\URS\URSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListURS extends ListRecords
{
    protected static string $resource = URSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Nomor URS'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
