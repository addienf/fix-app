<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource;
use App\Jobs\SendGenericNotif;
use App\Jobs\Warehouse\SendSerahTerimaBahanNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditSerahTerimaBahan extends EditRecord
{
    protected static string $resource = SerahTerimaBahanResource::class;
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
