<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPengecekanElectrical extends EditRecord
{
    protected static string $resource = PengecekanElectricalResource::class;

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
