<?php

namespace App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Model;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait TabelBarang
{
    use SimpleFormResource;
    protected static function informasiMaterial(): Section
    {
        return
            Section::make('Informasi Material')
            ->collapsible()
            ->schema([
                TableRepeater::make('detail')
                    ->relationship('details')
                    ->label('')
                    ->schema([
                        self::textInput('nama_material', 'Nama Material'),
                        self::textInput('kode_material', 'Kode Material'),
                        self::textInput('jumlah', 'Jumlah Material'),
                        self::textInput('satuan', 'Satuan'),
                        self::textInput('kondisi', 'Kondisi Material'),
                        self::getStatusBarang()
                    ])
                    ->orderColumn(false)
                    ->addActionLabel('Tambah Material')

            ]);
    }

    private static function getStatusBarang()
    {
        return
            Select::make('status_barang')
            ->required()
            ->options([
                1 => 'Diterima',
                0 => 'Ditolak',
            ]);
    }

    private static function getSignature()
    {
        return
            Section::make('PIC')
            ->collapsible()
            ->relationship('pic')
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                    'lg' => 2,
                ])
                    ->schema([
                        Section::make('')
                            ->label('')
                            ->hidden(fn($record) => filled($record?->diterima_ttd))
                            ->schema([
                                self::textInput('diterima_name', 'Diterima Oleh')
                                    ->required(false),

                                self::signatureInput(
                                    "diterima_ttd",
                                    '',
                                    'Purchasing/PenerimaanBarang/Signature'
                                )
                                    ->required(false)
                                    ->columnSpanFull(),
                            ]),

                        Section::make('')
                            ->label('')
                            ->hidden(
                                fn(string $operation, ?Model $record): bool =>
                                $operation === 'create' || filled($record?->diketahui_ttd)
                            )
                            ->schema([
                                self::textInput('diketahui_name', 'Diketahui Oleh')
                                    ->required(false),

                                self::signatureInput(
                                    "diketahui_ttd",
                                    '',
                                    'Purchasing/PenerimaanBarang/Signature'
                                )
                                    ->required(false)
                                    ->columnSpanFull(),
                            ])
                    ])
            ]);
    }
}
