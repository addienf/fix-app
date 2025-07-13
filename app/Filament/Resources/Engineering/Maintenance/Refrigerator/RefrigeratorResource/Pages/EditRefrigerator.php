<?php

namespace App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefrigerator extends EditRecord
{
    protected static string $resource = RefrigeratorResource::class;

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
