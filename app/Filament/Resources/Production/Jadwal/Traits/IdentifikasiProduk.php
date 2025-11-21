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

trait IdentifikasiProduk
{
    use SimpleFormResource;
    protected static function identifikasiProdukSection(): Section
    {
        return
            Section::make('Identifikasi Produk')
            ->collapsible()
            ->schema([
                TableRepeater::make('identifikasiProduks')
                    ->relationship('identifikasiProduks')
                    ->label('')
                    ->schema([

                        self::textInput('nama_alat', 'Nama Alat'),

                        self::textInput('tipe', 'Tipe/Model'),

                        self::textInput('batch_code', 'Batch (A/B/C)')
                            ->reactive()
                            ->hidden(fn($operation) => $operation === 'edit')
                            ->afterStateUpdated(function ($state, callable $set) {

                                $prefix = strtoupper(Str::random(4));
                                $mid = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                                $monthCode = chr(64 + now()->month);
                                $year = now()->format('y');
                                $generated = $prefix . $mid . strtoupper($state) . $monthCode . $year;
                                $set('no_seri', $generated);
                            }),

                        self::textInput('no_seri', 'Nomor Seri')
                            ->reactive()
                            ->default('')
                            ->dehydrated(true),

                        // TextInput::make('batch_code')
                        //     ->label('Batch (A/B/C)')
                        //     ->reactive()
                        //     ->hidden(fn($operation) => $operation === 'edit')
                        //     ->afterStateUpdated(function ($state, callable $set) {

                        //         $prefix = strtoupper(Str::random(4));
                        //         $mid = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        //         $monthCode = chr(64 + now()->month);
                        //         $year = now()->format('y');
                        //         $generated = $prefix . $mid . strtoupper($state) . $monthCode . $year;
                        //         $set('no_seri', $generated);
                        //     }),

                        // TextInput::make('no_seri')
                        //     ->label('Nomor Seri')
                        //     ->reactive()
                        //     ->default('')
                        //     ->dehydrated(true)
                        //     ->required(),

                        self::textInput('custom_standar', 'Custom/Stardar'),

                        self::textInput('jumlah', 'Quantity')->numeric(),

                    ])
                    ->deletable(true)
                    ->addable(true)
                    ->reorderable(false)
                    ->columnSpanFull(),

            ]);
    }
}
