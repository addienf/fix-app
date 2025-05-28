<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSpesifikasiProduct extends CreateRecord
{
    protected static string $resource = SpesifikasiProductResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Data Spesifikasi Berhasil Di Buat')
            ->success()
            // ->body('The User has been added and is ready to be edited if needed.')
            ->actions([
                Action::make('view')->label('View Data')->button()
                    ->url(EditSpesifikasiProduct::getUrl(['record' => $this->record])),
            ]);
    }
}
