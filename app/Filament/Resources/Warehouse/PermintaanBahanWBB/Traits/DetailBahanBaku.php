<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailBahanBaku
{
    use SimpleFormResource, HasAutoNumber;
    protected static function detailBahanBakuSection(): Section
    {
        return
            Section::make('List Detail Bahan Baku')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([
                        TableRepeater::make('details')
                            ->label('')
                            // ->relationship('details')
                            ->schema([

                                self::textInput('bahan_baku', 'Bahan Baku'),

                                self::textInput('spesifikasi', 'Spesifikasi'),

                                self::textInput('jumlah', 'Jumlah')
                                    ->numeric(),

                                self::textareaInput('keperluan_barang', 'Keperluan Barang')
                                    // ->maxLength(255)
                                    ->rows(1),

                            ])
                            ->columns(4)
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Detail Permintaan')
                    ])
            ]);
    }
}
