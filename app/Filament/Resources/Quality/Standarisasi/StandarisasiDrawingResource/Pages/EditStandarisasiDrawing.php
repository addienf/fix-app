<?php

namespace App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource;
use App\Jobs\Quality\SendStandarisasiDrawingNotif;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use App\Notifications\Quality\StandarisasiDrawingNotif;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditStandarisasiDrawing extends EditRecord
{
    protected static string $resource = StandarisasiDrawingResource::class;

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
                'standarisasi_drawing_pics',
                'standarisasi_drawing_id',
                'check_signature',
                'check_name',
                GenericNotification::class,
                '/admin/quality/standarisasi-gambar-kerja',
                'Data standarisasi gambar kerja berhasil dibuat',
                'Ada data standarisasi gambar kerja yang telah Anda tanda tangani.'
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
