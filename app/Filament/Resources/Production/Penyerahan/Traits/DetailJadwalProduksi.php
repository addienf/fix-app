<?php

namespace App\Filament\Resources\Production\Penyerahan\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailJadwalProduksi
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getDetailJadwalProduksiSection(): Section
    {
        return
            Section::make('Detail Jadwal Produksi')
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

                        self::textInput('tipe', 'Tipe/Model')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('volume', 'Volume')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('jumlah', 'Jumlah')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('no_spk', 'No SPK')
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
