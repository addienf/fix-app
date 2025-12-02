<?php

namespace App\Filament\Resources\Production\Jadwal\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Str;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait DetailJadwalProduksi
{
    use SimpleFormResource;
    protected static function detailJadwalProduksiSection(): Section
    {
        return
            Section::make('Detail Jadwal Produksi')
            ->collapsible()
            ->schema([
                TableRepeater::make('details')
                    ->relationship('details')
                    ->label('')
                    ->schema([

                        self::textInput('pekerjaan', 'Pekerjaan'),

                        self::textInput('pekerja', 'Yang Mengerjakan'),

                        self::dateInput('tanggal_mulai', 'Tanggal Mulai'),

                        self::dateInput('tanggal_selesai', 'Tanggal Selesai'),
                    ])
                    ->reorderable(false)
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Pekerja'),
            ]);
    }
}
