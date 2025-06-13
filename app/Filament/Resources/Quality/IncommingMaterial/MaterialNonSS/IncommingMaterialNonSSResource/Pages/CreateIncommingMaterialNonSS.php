<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncommingMaterialNonSS extends CreateRecord
{
    protected static string $resource = IncommingMaterialNonSSResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
