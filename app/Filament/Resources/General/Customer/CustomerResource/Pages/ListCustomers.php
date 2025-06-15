<?php

namespace App\Filament\Resources\General\Customer\CustomerResource\Pages;

use App\Filament\Resources\General\Customer\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Customer'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
