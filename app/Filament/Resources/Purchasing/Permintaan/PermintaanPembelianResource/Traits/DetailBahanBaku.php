<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;

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

                        Repeater::make('details')
                            ->relationship('details')
                            ->schema([

                                Grid::make(4)
                                    ->schema([

                                        self::textInput('kode_barang', 'Kode Barang'),

                                        self::textInput('nama_barang', 'Nama Barang')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::textInput('jumlah', 'Jumlah')->numeric()
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::textareaInput('keterangan', 'Keterangan')
                                            // ->columnSpanFull(false)
                                            ->columnSpan(1)
                                            ->rows(1),

                                    ])

                            ])
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false)
                            ->columnSpanFull()

                    ])

            ]);
    }
}
