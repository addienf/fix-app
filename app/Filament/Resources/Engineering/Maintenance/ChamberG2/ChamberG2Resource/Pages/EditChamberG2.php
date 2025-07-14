<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChamberG2 extends EditRecord
{
    protected static string $resource = ChamberG2Resource::class;

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
