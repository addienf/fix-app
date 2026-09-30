<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailBahanBaku
{
    use SimpleFormResource;
    protected static function detailBahanSection(): Section
    {
        return
            Section::make('List Detail Bahan Baku')
            ->collapsible()
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                    'lg' => 2,
                ])
                    ->schema([
                        TableRepeater::make('details')
                            ->label('')
                            ->relationship('details')
                            ->schema([

                                static::textInput('bahan_baku', 'Bahan Baku')
                                    ->required(false)
                                    ->readOnly(fn(Get $get) => $get('../../jenis_serahterima') === 'Engineer'),
                                static::textInput('spesifikasi', 'Spesifikasi')
                                    ->required(false)
                                    ->readOnly(fn(Get $get) => $get('../../jenis_serahterima') === 'Engineer'),
                                static::textInput('jumlah', 'Jumlah')
                                    ->required(false)
                                    ->readOnly(fn(Get $get) => $get('../../jenis_serahterima') === 'Engineer'),
                                static::textareaInput('keperluan_barang', 'Keperluan Barang')
                                    ->required(false)
                                    ->rows(1)
                                    ->readOnly(fn(Get $get) => $get('../../jenis_serahterima') === 'Engineer'),

                            ])
                            ->columns(4)
                            ->addable(fn(Get $get) => $get('jenis_serahterima') !== 'Engineer')
                            ->deletable(fn(Get $get) => $get('jenis_serahterima') !== 'Engineer')
                            ->reorderable(false)
                            ->addActionLabel('Tambah Data')
                    ])
            ]);
    }
}
