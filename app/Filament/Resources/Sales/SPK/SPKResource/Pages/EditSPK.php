<?php

namespace App\Filament\Resources\Sales\SPK\SPKResource\Pages;

use App\Filament\Resources\Sales\SPK\SPKResource;
use App\Jobs\Sales\SendSpkMarketingNotif;
use App\Jobs\SendGenericNotif;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Notifications\GenericNotification;
use App\Notifications\Sales\SpkMarketingNotif;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditSPK extends EditRecord
{
    protected static string $resource = SPKResource::class;
    protected ?SPKMarketing $originalRecord = null;
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
