<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateChamberR2 extends CreateRecord
{
    protected static string $resource = ChamberR2Resource::class;

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
                ['engineering'],
                GenericNotification::class,
                '/admin/engineering/stability-chamber',
                'Data Stability Chamber berhasil dibuat',
                'Ada data Stability Chamber yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Stability Chamber';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
