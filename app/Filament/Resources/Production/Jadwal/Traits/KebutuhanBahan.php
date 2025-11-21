<?php

namespace App\Filament\Resources\Production\Jadwal\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait KebutuhanBahan
{
    use SimpleFormResource;
    protected static function kebutuhanBahanSection(): Section
    {
        return
            Section::make('Kebutuhan bahan/alat')
            ->collapsible()
            ->schema([
                TableRepeater::make('sumbers')
                    ->relationship('sumbers')
                    ->label('')
                    ->schema([

                        self::textInput('bahan_baku', 'Nama Bahan Baku'),

                        self::textInput('spesifikasi', 'Spesifikasi'),

                        self::textInput('jumlah', 'Quantity'),

                        self::textInput('status', 'Status (Diterima atau Belum)'),

                        self::textInput('keperluan', 'Keperluan'),
                    ])
                    ->deletable(true)
                    ->reorderable(false)
                    ->addable(true)
                    ->columnSpanFull(),
            ]);
    }
}
