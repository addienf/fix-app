<?php

namespace App\Filament\Resources\Quality\Standarisasi\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait IdentitasGambarKerja
{
    use SimpleFormResource, HasAutoNumber;
    protected static function identifikasiGambarKerjaSection()
    {
        return Section::make('Identitas Gambar Kerja')
            ->collapsible()
            ->relationship('identitas')
            ->schema([

                Grid::make(3)
                    ->schema([
                        self::textInput('judul_gambar', 'Judul Gambar'),

                        self::textInput('no_gambar', 'No Gambar'),

                        self::dateInput('tanggal_pembuatan', 'Tanggal Pembuatan'),
                    ]),

                self::buttonGroup('revisi', 'Revisi')
                    ->helperText('*Jika Ya Revisi Ke ')
                    ->reactive()
                    ->columnSpanFull(),

                self::textInput('revisi_ke', 'Revisi Ke')
                    ->hidden(fn(Get $get) => $get('revisi') != 1),

                Grid::make(2)
                    ->schema([
                        self::textInput('nama_pembuat', 'Nama Pembuat'),

                        self::textInput('nama_pemeriksa', 'Nama Pemeriksa'),
                    ])

            ]);
    }

    protected static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                1 => 'Ya',
                0 => 'Tidak',
            ])
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->default('individual');
    }
}
