<?php

namespace App\Filament\Resources\Engineering\Pelayanan\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait IdentitasAlat
{
    use SimpleFormResource;
    public static function getIdentitasAlatSection()
    {
        return
            Section::make('B. Identitas Alat')
            ->collapsible()
            ->schema([
                TableRepeater::make('details')
                    ->relationship('details')
                    ->label('')
                    ->schema([

                        TextInput::make('nama_alat')
                            ->label('Nama Alat')
                            ->required(),

                        TextInput::make('tipe')
                            ->label('Type/Model')
                            ->required(),

                        TextInput::make('nomor_seri')
                            ->label('Nomor Seri')
                            ->required(),

                        Textarea::make('deskripsi')
                            ->rows(1)
                            ->label('Deskripsi'),

                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->label('Quantity'),
                    ])
                    ->columnSpanFull()
                    ->addable(false)
                    ->reorderable(false)
                    ->deletable(false)
            ])
            ->columns(2);
    }
}
