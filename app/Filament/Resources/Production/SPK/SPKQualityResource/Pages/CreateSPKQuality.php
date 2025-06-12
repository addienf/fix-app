<?php

namespace App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;

use App\Filament\Resources\Production\SPK\SPKQualityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSPKQuality extends CreateRecord
{
    protected static string $resource = SPKQualityResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
