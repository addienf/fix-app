<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource\Pages;

use App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditChamberR2 extends EditRecord
{
    protected static string $resource = ChamberR2Resource::class;

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
                'chamber_r2_pics',
                'r2_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/engineering/chamber-r2',
                'Data chamber r2 berhasil dibuat',
                'Ada data chamber r2 yang telah Anda tanda tangani.'
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
