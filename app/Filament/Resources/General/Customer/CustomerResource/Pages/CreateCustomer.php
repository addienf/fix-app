<?php

namespace App\Filament\Resources\General\Customer\CustomerResource\Pages;

use App\Filament\Resources\General\Customer\CustomerResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Customer';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
