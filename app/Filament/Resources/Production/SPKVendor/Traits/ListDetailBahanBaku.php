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
                    ->relationship('details')
                    ->label('')
                    ->schema([

                        self::textInput('nama_bahan', 'Bahan Baku'),
                        TextInput::make('spesifikasi'),
                        TextInput::make('jumlah'),
                        TextInput::make('keperluan')
                            ->required(false),
                    ])
                    ->addActionLabel('Tambah Bahan Baku')
                    // ->deletable(false)
                    ->reorderable(false)
                    ->columnSpanFull()
            ]);
    }
}
