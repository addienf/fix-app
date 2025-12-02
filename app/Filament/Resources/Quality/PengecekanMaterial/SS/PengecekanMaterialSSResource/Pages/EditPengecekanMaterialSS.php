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
