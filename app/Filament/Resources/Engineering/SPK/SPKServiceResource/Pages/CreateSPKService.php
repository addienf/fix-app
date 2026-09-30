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
                ['customer_care', 'engineering'],
                GenericNotification::class,
                '/admin/engineering/spk-pelayanan-pelanggan',
                'Data SPK Pelayanan Pelanggan berhasil dibuat',
                'Ada data SPK Pelayanan Pelanggan yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     if (empty($data['no_spk_service'])) {
    //         $data['no_spk_service'] = self::generateAutoNumber(
    //             table: 'spk_services',
    //             column: 'no_spk_service',
    //             prefix: 'QKS',
    //             section: $data['section'],
    //             type: 'SPK'
    //         );
    //     }

    //     return $data;
    // }

    public function getTitle(): string
    {
        return 'Tambah Data SPK Pelayanan Pelanggan';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
