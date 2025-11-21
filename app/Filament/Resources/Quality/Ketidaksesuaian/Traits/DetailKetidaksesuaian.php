<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailKetidaksesuaian
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getDetailKetidaksesuaianSection()
    {
        return
            Section::make('B. Detail Ketidaksesuaian Produk & Material')
            ->schema([
                TableRepeater::make('details')
                    ->relationship('details')
                    ->label('')
                    ->schema([

                        self::textInput('nama_produk', 'Nama Produk'),
                        self::textInput('serial_number', 'Serial Number'),
                        self::textInput('ketidaksesuaian', 'ketidaksesuaian'),
                        self::textInput('jumlah', 'Jumlah')->numeric(),
                        self::textInput('satuan', 'Satuan'),
                        self::textInput('keterangan', 'Keterangan'),
                    ])
                    ->deletable(true)
                    ->addable(true)
                    ->reorderable(false)
                    ->columnSpanFull(),
            ]);
    }
}
