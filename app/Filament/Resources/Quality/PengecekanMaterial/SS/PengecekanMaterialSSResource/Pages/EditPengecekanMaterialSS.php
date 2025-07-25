<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPengecekanMaterialSS extends EditRecord
{
    protected static string $resource = PengecekanMaterialSSResource::class;
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
                'pengecekan_material_ss_pics',
                'pengecekan_material_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/quality/pengecekan-material-stainless-steel',
                'Data pengecekan material stainless steel berhasil dibuat',
                'Ada data pengecekan material stainless steel yang telah Anda tanda tangani.'
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['details'] = request()->input('details') ?? [];

        return $data;
    }
}
