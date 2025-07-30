<?php

namespace App\Filament\Resources\Engineering\Complain\ComplainResource\Pages;

use App\Filament\Resources\Engineering\Complain\ComplainResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateComplain extends CreateRecord
{
    protected static string $resource = ComplainResource::class;

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
                '/admin/engineering/complaint',
                'Data Complaint berhasil dibuat',
                'Ada data Complaint yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Complaint';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
