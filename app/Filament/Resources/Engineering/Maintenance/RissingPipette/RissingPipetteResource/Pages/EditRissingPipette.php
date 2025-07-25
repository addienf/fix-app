<?php

namespace App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditRissingPipette extends EditRecord
{
    protected static string $resource = RissingPipetteResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                'rissing_pipette_pics',
                'rissing_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/engineering/rissing-pipette',
                'Data rissing pipette berhasil dibuat',
                'Ada data rissing pipette yang telah Anda tanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
