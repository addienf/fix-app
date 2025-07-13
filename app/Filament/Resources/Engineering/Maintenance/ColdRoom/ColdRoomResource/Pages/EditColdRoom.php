<?php

namespace App\Filament\Resources\Engineering\Maintenance\ColdRoom\ColdRoomResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ColdRoom\ColdRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditColdRoom extends EditRecord
{
    protected static string $resource = ColdRoomResource::class;

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
