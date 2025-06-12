<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenyerahanProdukJadi extends CreateRecord
{
    protected static string $resource = PenyerahanProdukJadiResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
