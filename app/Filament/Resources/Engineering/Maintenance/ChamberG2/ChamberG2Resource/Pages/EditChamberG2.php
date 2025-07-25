<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditChamberG2 extends EditRecord
{
    protected static string $resource = ChamberG2Resource::class;

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
                'chamber_g2_pics',
                'g2_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/engineering/chamber-g2',
                'Data chamber g2 berhasil dibuat',
                'Ada data chamber g2 yang telah Anda tanda tangani.'
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
