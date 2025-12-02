<?php

namespace App\Filament\Resources\General\URS\URSResource\Pages;

use App\Filament\Resources\General\URS\URSResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateURS extends CreateRecord
{
    protected static string $resource = URSResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                ['sales', 'super_admin'],
                GenericNotification::class,
                '/admin/general/penomoran-urs',
                'Data Penomoran URS Warehouse berhasil dibuat',
                'Ada data Penomoran URS Warehouse yang harus ditanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Nomor URS';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
