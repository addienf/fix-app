<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWalkinChamber extends CreateRecord
{
    protected static string $resource = WalkinChamberResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Walk-in Chamber';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
