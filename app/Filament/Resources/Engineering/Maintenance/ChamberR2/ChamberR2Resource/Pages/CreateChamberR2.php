<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChamberR2 extends CreateRecord
{
    protected static string $resource = ChamberR2Resource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Chamber R2';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
