<?php

namespace App\Filament\Resources\Production\Jadwal\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait TimelineProduksi
{
    use SimpleFormResource;
    protected static function timelineProduksiSection(): Section
    {
        return Section::make('Timeline Produksi')
            ->schema([
                TableRepeater::make('timelines')
                    ->relationship('timelines')
                    ->label('')
                    ->schema([

                        self::textInput('task', 'Task'),

                        self::dateInput('tanggal_mulai', 'Tanggal Mulai'),

                        self::dateInput('tanggal_selesai', 'Tanggal Selesai'),
                    ])
                    ->deletable(true)
                    ->addable(true)
                    ->reorderable(false)
                    ->columnSpanFull(),
            ]);
    }
}
