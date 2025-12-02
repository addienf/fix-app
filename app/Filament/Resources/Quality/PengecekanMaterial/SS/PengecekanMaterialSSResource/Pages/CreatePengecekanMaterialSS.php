<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreatePengecekanMaterialSS extends CreateRecord
{
    protected static string $resource = PengecekanMaterialSSResource::class;
    protected static bool $canCreateAnother = false;
    protected array $detailData = [];

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
                '/admin/quality/pengecekan-material-stainless-steel',
                'Data Pengecekan Material Stainless Steel berhasil dibuat',
                'Ada data Pengecekan Material Stainless Steel yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reset')
                ->label('Reset Form')
                ->color('gray')
                ->action(fn() => $this->form->fill()),
        ];
    }

    public function getTitle(): string
    {
        return 'Tambah Data Pengecekan Material';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
