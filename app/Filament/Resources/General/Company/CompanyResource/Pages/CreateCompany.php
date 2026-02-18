<?php

namespace App\Filament\Resources\General\Company\CompanyResource\Pages;

use App\Filament\Resources\General\Company\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Company';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
