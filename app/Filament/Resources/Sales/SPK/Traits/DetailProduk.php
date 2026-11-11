<?php

namespace App\Filament\Resources\Sales\SPK\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailProduk
{
    use SimpleFormResource;
    protected static function detailProdukSection(): Section
    {
        return Section::make('Detail Produk Yang Dipesan')
            ->hiddenOn(operations: 'edit')
            ->collapsible()
            ->schema([

                TableRepeater::make('details')
                    ->label('')
                    ->schema([

                        self::textInput('name', 'Nama Produk')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('quantity', 'Jumlah Pesanan')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('no_urs', 'No URS')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                    ])
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false)
                    ->helperText('*Salinan URS Wajib diberikan kepada Departemen Produksi'),

            ]);
    }
}
