<?php

namespace App\Filament\Resources\Quality\Release\ProductReleaseResource\Pages;

use App\Filament\Resources\Quality\Release\ProductReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductReleases extends ListRecords
{
    protected static string $resource = ProductReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Product Release'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
