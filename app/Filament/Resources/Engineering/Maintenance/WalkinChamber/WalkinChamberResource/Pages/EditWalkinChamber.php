<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditWalkinChamber extends EditRecord
{
    protected static string $resource = WalkinChamberResource::class;

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
                'walkin_chamber_pics',
                'walk_in_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/engineering/walkin-chamber',
                'Data walk-in chamber berhasil dibuat',
                'Ada data walk-in chamber yang telah Anda tanda tangani.'
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
