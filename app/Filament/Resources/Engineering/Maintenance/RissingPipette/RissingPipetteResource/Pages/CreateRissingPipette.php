<?php

namespace App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateRissingPipette extends CreateRecord
{
    protected static string $resource = RissingPipetteResource::class;

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
                '/admin/engineering/rissing-pipette',
                'Data Rissing Pipette berhasil dibuat',
                'Ada data Rissing Pipette yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Rissing Pipette';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
