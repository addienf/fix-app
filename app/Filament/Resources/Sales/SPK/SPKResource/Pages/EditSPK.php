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

    protected function afterSave(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                'spk_marketing_pics',
                'spk_marketing_id',
                'receive_signature',
                'receive_name',
                GenericNotification::class,
                '/admin/sales/spk-marketing',
                'Data spk marketing berhasil dibuat',
                'Ada data spk marketing yang telah Anda tanda tangani.'
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
