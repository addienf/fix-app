<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditKelengkapanMaterialSS extends EditRecord
{
    protected static string $resource = KelengkapanMaterialSSResource::class;

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
                'kelengkapan_material_ss_pics',
                'kelengkapan_material_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/quality/kelengkapan-material',
                'Data kelengkapan material stainless steel berhasil dibuat',
                'Ada data kelengkapan material stainless steel yang telah Anda tanda tangani.'
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
