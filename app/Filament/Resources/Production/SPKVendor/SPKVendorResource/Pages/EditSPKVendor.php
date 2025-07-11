<?php

namespace App\Filament\Resources\Production\SPKVendor\SPKVendorResource\Pages;

use App\Filament\Resources\Production\SPKVendor\SPKVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPKVendor extends EditRecord
{
    protected static string $resource = SPKVendorResource::class;

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
