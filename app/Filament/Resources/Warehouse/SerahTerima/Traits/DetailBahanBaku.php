<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailBahanBaku
{
    use SimpleFormResource, HasAutoNumber;
    protected static function detailBahanSection(): Section
    {
        return Section::make('List Detail Bahan Baku')
            ->collapsible()
            ->schema([

                Grid::make(2)
                    ->schema([

                        TableRepeater::make('details')
                            ->label('')
                            ->relationship('details')
                            ->schema([

                                self::textInput('bahan_baku', 'Bahan Baku')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                self::textInput('spesifikasi', 'Spesifikasi')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                self::textInput('jumlah', 'Jumlah')->numeric()
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                self::textareaInput('keperluan_barang', 'Keperluan Barang')
                                    ->rows(1)
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ])

                            ])
                            ->columns(4)
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false)
                            ->columnSpanFull()

                    ])

            ]);
    }
}
