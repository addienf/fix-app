<?php

namespace App\Filament\Resources\Engineering\Maintenance\ColdRoom\ColdRoomResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ColdRoom\ColdRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateColdRoom extends CreateRecord
{
    protected static string $resource = ColdRoomResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Cold Room';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
