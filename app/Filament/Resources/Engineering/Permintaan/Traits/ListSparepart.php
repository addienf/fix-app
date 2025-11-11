<?php

namespace App\Filament\Resources\Engineering\Permintaan\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait ListSparepart
{
    use SimpleFormResource;
    public static function getListSparepartSection()
    {
        return Section::make('List Spareparts')
            ->collapsible()
            ->schema([
                TableRepeater::make('details')
                    ->relationship('details')
                    ->label('')
                    ->schema([
                        TextInput::make('bahan_baku')
                            ->required()
                            ->label('Nama Barang'),

                        TextInput::make('spesifikasi')
                            ->required(),

                        TextInput::make('jumlah')
                            ->required()
                            ->numeric(),

                        TextInput::make('keperluan_barang')
                            ->required()
                            ->label('Keperluan Barang'),
                    ])
                    ->columns(4)
                    ->defaultItems(1)
                    ->collapsible()
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Data Petugas'),
            ]);
    }
}
