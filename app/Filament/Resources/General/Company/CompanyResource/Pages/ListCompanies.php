<?php

namespace App\Filament\Resources\General\Company\CompanyResource\Pages;

use App\Filament\Resources\General\Company\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Company'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
