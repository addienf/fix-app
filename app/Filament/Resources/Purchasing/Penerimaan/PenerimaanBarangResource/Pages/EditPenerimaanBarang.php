<?php

namespace App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Pages;

use App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaanBarang extends EditRecord
{
    protected static string $resource = PenerimaanBarangResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
