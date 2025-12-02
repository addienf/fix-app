<?php

namespace App\Filament\Resources\Engineering\SPK\SPKServiceResource\Pages;

use App\Filament\Resources\Engineering\SPK\SPKServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateSPKService extends CreateRecord
{
    protected static string $resource = SPKServiceResource::class;

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
                '/admin/engineering/spk-service',
                'Data SPK Service berhasil dibuat',
                'Ada data SPK Service yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data SPK Service';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
