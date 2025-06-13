<?php

namespace App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncommingMaterial extends EditRecord
{
    protected static string $resource = IncommingMaterialResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
