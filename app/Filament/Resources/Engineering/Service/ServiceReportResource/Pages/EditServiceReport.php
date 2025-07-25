<?php

namespace App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;

use App\Filament\Resources\Engineering\Service\ServiceReportResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditServiceReport extends EditRecord
{
    protected static string $resource = ServiceReportResource::class;

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
                'service_report_pics',
                'service_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/engineering/service-report',
                'Data service report berhasil dibuat',
                'Ada data service report yang telah Anda tanda tangani.'
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
