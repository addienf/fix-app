<?php

namespace App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;

use App\Filament\Resources\Production\SPK\SPKQualityResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditSPKQuality extends EditRecord
{
    protected static string $resource = SPKQualityResource::class;

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
                'spk_quality_pics',
                'spk_qualities_id',
                'receive_signature',
                'receive_name',
                GenericNotification::class,
                '/admin/produksi/spk-quality',
                'Data spk quality berhasil dibuat',
                'Ada data spk quality yang telah Anda tanda tangani.'
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
