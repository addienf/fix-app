<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\Traits;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait DetailBahanBaku
{
    use SimpleFormResource;
    protected static function detailBahanBakuSection(): Section
    {
        return Section::make('List Detail Bahan Baku')
            ->collapsible()
            ->schema([

                Grid::make(2)
                    ->schema([

                        TableRepeater::make('details')
                            ->relationship('details')
                            ->schema([

                                self::textInput('bahan_baku', 'Bahan Baku'),

                                self::textInput('spesifikasi', 'Spesifikasi'),

                                self::textInput('jumlah', 'Jumlah')->numeric(),

                                self::textareaInput('keperluan_barang', 'Keperluan Barang')->rows(1),

                            ])
                            ->reorderable(false)
                            ->columnSpanFull()
                    ])
            ]);
    }
}
