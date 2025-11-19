<?php

namespace App\Filament\Resources\Production\SPK\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailProduk
{
    use SimpleFormResource;
    protected static function getDetailProdukSection(): Section
    {
        return
            Section::make('Detail Produk Yang Dipesan')
            // ->hiddenOn(operations: 'edit')
            ->collapsible()
            ->schema([
                TableRepeater::make('details')
                    ->relationship('details')
                    ->label('')
                    ->schema([
                        self::textInput('nama_produk', 'Nama Produk')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
                        self::textInput('jumlah', 'Jumlah Pesanan')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
                        self::textInput('no_urs', 'No URS')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('rencana_pengiriman', 'Rencana Pengiriman')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
                    ])
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false),
            ]);
    }
}
