<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait Pemeriksaan
{
    use SimpleFormResource;
    public static function getPemeriksaanSection()
    {

        return
            Section::make('B Identitas Alat')
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

                        TextInput::make('resolusi')
                            ->label('Resolusi'),

                        TextInput::make('titik_ukur')
                            ->label('Titik Ukur'),

                        TextInput::make('quantity')
                            ->numeric()
                            ->label('Quantity'),
                    ])
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ])
                    // ->addable(false)
                    ->addActionLabel('Tambah Item')
                    ->reorderable(false)
                    ->deletable(false)
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
            ]);
    }
}
