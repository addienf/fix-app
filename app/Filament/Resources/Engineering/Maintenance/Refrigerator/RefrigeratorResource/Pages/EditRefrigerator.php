<?php

namespace App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditRefrigerator extends EditRecord
{
    protected static string $resource = RefrigeratorResource::class;

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
                'refrigerator_pics',
                'refrigerator_id',
                'approved_signature',
                'approved_name',
                GenericNotification::class,
                '/admin/engineering/refrigerator',
                'Data refrigerator berhasil dibuat',
                'Ada data refrigerator yang telah Anda tanda tangani.'
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
