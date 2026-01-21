<?php

namespace App\Filament\Resources\Production\SPKVendor\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait ListDetailBahanBaku
{
    use SimpleFormResource;
    protected static function ListDetailBahanBakuSection(): Section
    {
        return
            Section::make('List Detail Bahan Baku')
            ->collapsible()
            ->hiddenOn('edit')
            ->schema([

                TableRepeater::make('details')
                    ->label('')
                    ->schema([

                        self::textInput('bahan_baku', 'Bahan Baku')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        TextInput::make('spesifkikasi'),
                        TextInput::make('jumlah'),
                        TextInput::make('keperluan_barang'),
                        // self::textInput('spesifikasi', 'Spesifikasi')
                        //     ->extraAttributes([
                        //         'readonly' => true,
                        //         'style' => 'pointer-events: none;'
                        //     ]),

                        // self::textInput('jumlah', 'Jumlah')
                        //     ->numeric()
                        //     ->extraAttributes([
                        //         'readonly' => true,
                        //         'style' => 'pointer-events: none;'
                        //     ])
                        //     ->required(false),

                        // self::textareaInput('keperluan_barang', 'Keperluan Barang')
                        //     ->rows(1)
                        //     ->extraAttributes([
                        //         'readonly' => true,
                        //         'style' => 'pointer-events: none;'
                        //     ])
                        //     ->required(false),

                        // Textarea::make('keperluan_barang')
                        //     ->required()
                        //     ->rows(1)
                        //     ->label('Keperluan Barang')
                        //     ->extraAttributes([
                        //         'readonly' => true,
                        //         'style' => 'pointer-events: none;'
                        //     ])

                    ])
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false)
                    ->columnSpanFull()
            ]);
    }
}
