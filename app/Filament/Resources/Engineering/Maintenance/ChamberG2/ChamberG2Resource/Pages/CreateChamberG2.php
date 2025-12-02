<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateChamberG2 extends CreateRecord
{
    protected static string $resource = ChamberG2Resource::class;

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
                '/admin/engineering/chamber-g2',
                'Data Chamber G2 berhasil dibuat',
                'Ada data Chamber G2 yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Chamber G2';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
