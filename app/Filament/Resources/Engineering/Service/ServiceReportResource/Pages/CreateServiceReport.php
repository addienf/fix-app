<?php

namespace App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;

use App\Filament\Resources\Engineering\Service\ServiceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateServiceReport extends CreateRecord
{
    protected static string $resource = ServiceReportResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                ['sales', 'super_admin'],
                GenericNotification::class,
                '/admin/engineering/service-report',
                'Data Service Report berhasil dibuat',
                'Ada data Service Report yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Service Report';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
