<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
                            ->relationship('permintaanDetails')
                            ->schema([

                                self::textInput('bahan_baku', 'Bahan Baku'),

                                self::textInput('spesifikasi', 'Spesifikasi')->required(false),

                                self::textInput('jumlah', 'Jumlah')->required(false),

                                self::textareaInput('keperluan_barang', 'Keperluan Barang')
                                    ->rows(1)->required(false),

                                Select::make(name: 'status_stock')
                                    ->label('Stock')
                                    ->required()
                                    ->options([
                                        'Tersedia' => 'Tersedia',
                                        'Tidak Tersedia' => 'Tidak Tersedia',
                                    ]),

                            ])
                            ->columns(4)
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Detail Permintaan')
                    ])
            ]);
    }
}
