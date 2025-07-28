<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreatePengecekanElectrical extends CreateRecord
{
    protected static string $resource = PengecekanElectricalResource::class;

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
                '/admin/quality/pengecekan-material-electrical',
                'Data Pengecekan Material Electrical berhasil dibuat',
                'Ada data Pengecekan Material Electrical yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Pengecekan Material Electrical';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
