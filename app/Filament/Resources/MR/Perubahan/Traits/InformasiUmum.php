<?php

namespace App\Filament\Resources\MR\Perubahan\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiUmum
{
    use SimpleFormResource;
    protected static function informasiUmum(): Section
    {
        return
            Section::make('Pemohon')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([

                        self::textInput('nama', 'Nama'),

                        self::dateInput('tanggal', 'Tanggal'),

                        self::selectDokumen(),

                        self::selectPerubahan(),

                        self::textInput('jenis_dokumen_lainnya', 'Jenis Dokumen Lainnya')
                            ->columnSpanFull()
                            ->visible(fn($get) => $get('jenis_dokumen') === 'lainnya'),
                    ]),
            ]);
    }

    protected static function selectDokumen(): Select
    {
        return
            Select::make('jenis_dokumen')
            ->required()
            ->live()
            ->options([
                'pedoman' => 'Pedoman Mutu',
                'prosedur' => 'Prosedur',
                'instruksi' => 'Instruksi Kerja',
                'formulir' => 'Formulir',
                'lainnya' => 'Lainnya',
            ]);
    }

    protected static function selectPerubahan(): Select
    {
        return
            Select::make('perubahan_diminta')
            ->required()
            ->options([
                'baru' => 'Dokumen Baru',
                'batal' => 'Pembatalan',
                'revisi' => 'Revisi Dokumen',
            ]);
    }
}
