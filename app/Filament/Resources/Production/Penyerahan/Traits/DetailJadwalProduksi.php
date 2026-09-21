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

                        self::textInput('nama_produk', 'Nama Produk'),

                        self::textInput('tipe', 'Tipe/Model'),

                        self::textInput('volume', 'Volume'),

                        self::textInput('jumlah', 'Jumlah'),

                        self::textInput('no_spk', 'No SPK'),

                    ])
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false),

            ]);
    }
}
