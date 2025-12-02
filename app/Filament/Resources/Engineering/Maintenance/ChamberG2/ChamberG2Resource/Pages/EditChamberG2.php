<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

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
