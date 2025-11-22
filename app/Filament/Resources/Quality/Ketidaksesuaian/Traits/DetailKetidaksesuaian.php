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
                        // ->extraAttributes([
                        //     'readonly' => true,
                        //     'style' => 'pointer-events: none;'
                        // ]),
                        self::textInput('serial_number', 'Serial Number'),
                        // ->extraAttributes([
                        //     'readonly' => true,
                        //     'style' => 'pointer-events: none;'
                        // ]),
                        self::textInput('ketidaksesuaian', 'ketidaksesuaian'),
                        self::textInput('jumlah', 'Jumlah')->numeric(),
                        // ->extraAttributes([
                        //     'readonly' => true,
                        //     'style' => 'pointer-events: none;'
                        // ]),
                        self::textInput('satuan', 'Satuan'),
                        self::textareaInput('keterangan', 'Keterangan')
                            ->rows(1),
                    ])
                    ->deletable(true)
                    ->addable(true)
                    ->reorderable(false)
                    ->columnSpanFull(),
            ]);
    }
}
