<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailBahanBaku
{
    use SimpleFormResource, HasAutoNumber;
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

                                self::textInput('kode_barang', 'Kode Barang')
                                    ->required(false),

                                self::textInput('nama_barang', 'Nama Barang'),

                                self::textInput('jumlah', 'Jumlah'),

                                self::textareaInput('keterangan', 'Keterangan')
                                    ->rows(1)
                                    ->required(false),

                            ])
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Detail Permintaan Pembelian')

                    ])

            ]);
    }
}
