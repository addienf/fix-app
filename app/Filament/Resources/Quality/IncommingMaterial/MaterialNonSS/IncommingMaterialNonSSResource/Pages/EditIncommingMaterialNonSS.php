<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditIncommingMaterialNonSS extends EditRecord
{
    protected static string $resource = IncommingMaterialNonSSResource::class;

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
