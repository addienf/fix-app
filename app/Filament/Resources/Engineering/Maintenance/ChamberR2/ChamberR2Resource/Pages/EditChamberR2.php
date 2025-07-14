<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChamberR2 extends EditRecord
{
    protected static string $resource = ChamberR2Resource::class;

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
