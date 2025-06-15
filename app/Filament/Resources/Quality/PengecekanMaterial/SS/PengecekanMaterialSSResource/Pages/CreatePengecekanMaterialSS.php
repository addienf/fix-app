<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengecekanMaterialSS extends CreateRecord
{
    protected static string $resource = PengecekanMaterialSSResource::class;
    protected static bool $canCreateAnother = false;
    protected array $detailData = [];

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
