<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChamberWalkinG2 extends CreateRecord
{
    protected static string $resource = ChamberWalkinG2Resource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Walk-in Chamber G2';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
