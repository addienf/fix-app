<?php

namespace App\Filament\Resources\Quality\Release\ProductReleaseResource\Pages;

use App\Filament\Resources\Quality\Release\ProductReleaseResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateProductRelease extends CreateRecord
{
    protected static string $resource = ProductReleaseResource::class;

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
                '/admin/quality/product-release',
                'Data Product Release berhasil dibuat',
                'Ada data Product Release yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Product Release';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
