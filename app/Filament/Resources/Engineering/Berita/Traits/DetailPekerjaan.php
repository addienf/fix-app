<?php

namespace App\Filament\Resources\Engineering\Berita\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

trait DetailPekerjaan
{
    use SimpleFormResource;
    public static function getDetailPekerjaanSection()
    {
        return
            Section::make('Detail Pekerjaan')
            ->relationship('detail')
            ->collapsible()
            ->schema([

                Select::make('jenis_pekerjaan')
                    ->required()
                    ->reactive()
                    ->label('Jenis Pekerjaan')
                    ->placeholder('Pilih Jenis Pekerjaan')
                    ->options([
                        'service' => 'Service',
                        'maintenance' => 'Maintenance',
                        'lainnya' => 'Lainnya',
                    ]),

                self::textInput('jenis_pekerjaan_lainnya', 'Jenis Pekerjaan Lainnya')
                    ->visible(fn($get) => in_array('lainnya', (array) $get('jenis_pekerjaan')))
                    ->required(fn($get) => in_array('lainnya', (array) $get('jenis_pekerjaan'))),

                TextInput::make('produk')
                    ->required()
                    ->label('Produk'),

                TextInput::make('serial_number')
                    ->required()
                    ->label('Serial Number'),

                Select::make('status_barang')
                    ->label('Status Barang')
                    ->columnSpan(
                        fn($get) =>
                        in_array('lainnya', (array) $get('jenis_pekerjaan')) ? 'full' : 1
                    )
                    ->options([
                        'yes' => 'Installed',
                        'wait' => 'Delivered',
                        'na' => 'N/A',
                    ])
                    ->required(),

                Textarea::make('desc_pekerjaan')
                    ->required()
                    ->label('Deskripsi Pekerjaan')
                    ->columnSpanFull(),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
            ]);
    }
}
