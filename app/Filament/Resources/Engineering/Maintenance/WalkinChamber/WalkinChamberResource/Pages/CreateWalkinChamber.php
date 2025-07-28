<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateWalkinChamber extends CreateRecord
{
    protected static string $resource = WalkinChamberResource::class;

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
                '/admin/engineering/walkin-chamber',
                'Data Walk-in Chamber berhasil dibuat',
                'Ada data Walk-in Chamber yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Walk-in Chamber';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
