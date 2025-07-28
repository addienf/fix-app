<?php

namespace App\Filament\Resources\Sales\SPK\SPKResource\Pages;

use App\Filament\Resources\Sales\SPK\SPKResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;

class CreateSPK extends CreateRecord
{
    protected static string $resource = SPKResource::class;
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
                '/admin/sales/spk-marketing',
                'Data SPK Marketing berhasil dibuat',
                'Ada data SPK Marketing yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data SPK';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
