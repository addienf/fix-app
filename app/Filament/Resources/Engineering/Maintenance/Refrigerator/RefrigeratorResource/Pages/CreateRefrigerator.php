<?php

namespace App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRefrigerator extends CreateRecord
{
    protected static string $resource = RefrigeratorResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Refrigerator';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
