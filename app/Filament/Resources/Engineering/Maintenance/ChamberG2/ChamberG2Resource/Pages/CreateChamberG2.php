<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChamberG2 extends CreateRecord
{
    protected static string $resource = ChamberG2Resource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Chamber G2';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
