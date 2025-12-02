<?php

namespace App\Filament\Resources\General\URS\URSResource\Pages;

use App\Filament\Resources\General\URS\URSResource;
use App\Models\General\Customer;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditURS extends EditRecord
{
    protected static string $resource = URSResource::class;

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
