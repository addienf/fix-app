<?php

namespace App\Filament\Resources\Sales\SPK\SPKResource\Pages;

use App\Filament\Resources\Sales\SPK\SPKResource;
use App\Jobs\Sales\SendSpkMarketingNotif;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPK extends EditRecord
{
    protected static string $resource = SPKResource::class;
    protected ?SPKMarketing $originalRecord = null;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        if ($this->record && $this->record->id) {
            dispatch(new SendSpkMarketingNotif($this->record));
        } else {
            dd('ini after save');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
