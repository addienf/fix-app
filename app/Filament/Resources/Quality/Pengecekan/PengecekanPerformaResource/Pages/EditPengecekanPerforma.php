<?php

namespace App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengecekanPerforma extends EditRecord
{
    protected static string $resource = PengecekanPerformaResource::class;

    protected static bool $canCreateAnother = false;

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
