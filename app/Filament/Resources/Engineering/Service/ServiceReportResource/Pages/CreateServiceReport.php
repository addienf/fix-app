<?php

namespace App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;

use App\Filament\Resources\Engineering\Service\ServiceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceReport extends CreateRecord
{
    protected static string $resource = ServiceReportResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Service Report';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
