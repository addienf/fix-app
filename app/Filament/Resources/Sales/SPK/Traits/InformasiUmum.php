<?php

namespace App\Filament\Resources\Sales\SPK\Traits;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    protected static function informasiUmumSection(): Section
    {
        $lastValue = SPKMarketing::latest('no_spk')->value('no_spk');

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make(2)
                    ->schema([

                        DatePicker::make('tanggal')
                            ->label('Rencana Pengiriman')
                            ->required()
                            ->displayFormat('M d Y'),

                        // self::textInput('no_spk', 'Nomor SPK')
                        //     ->hint('Format: XXX/QKS/MKT/SPK/MM/YY')
                        //     ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                        //     ->hiddenOn('edit')
                        //     ->unique(ignoreRecord: true),

                        self::autoNumberField2('no_spk', 'Nomor SPK', [
                            'prefix' => 'QKS',
                            'section' => 'MKT',
                            'type' => 'SPK',
                            'table' => 'spk_marketings',
                        ])
                            ->hiddenOn('edit')
                            ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia'),

                        self::selectSpecInput()
                            ->hiddenOn('edit'),

                        self::textInput('dari', 'Dari'),

                        self::textInput('no_order', 'Nomor Order')
                            ->unique(ignoreRecord: true),

                        self::textInput('kepada', 'Kepada'),
                    ]),

            ]);
    }

    // protected static function selectSpecInput(): Select
    // {
    //     return
    //         Select::make('spesifikasi_product_id')
    //         ->label('Nama Customer')
    //         ->placeholder('Pilih Nama Customer')
    //         ->reactive()
    //         ->required()
    //         ->options(function () {
    //             return
    //                 SpesifikasiProduct::with('urs.customer')
    //                 ->whereDoesntHave('spk')
    //                 ->get()
    //                 ->mapWithKeys(function ($item) {
    //                     $noUrs = $item->urs->no_urs ?? '-';
    //                     $customerName = $item->urs->customer->name ?? '-';
    //                     return [$item->id => "{$noUrs} - {$customerName}"];
    //                 });
    //         })
    //         ->afterStateUpdated(function ($state, callable $set) {
    //             if (!$state) return;

    //             $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'details.product'])->find($state);
    //             if (!$spesifikasi) return;

    //             $noUrs = $spesifikasi->urs?->no_urs ?? '-';

    //             $details = $spesifikasi->details->map(function ($detail) use ($noUrs) {
    //                 return [
    //                     'name' => $detail->product?->name ?? '-',
    //                     'quantity' => $detail?->quantity ?? '-',
    //                     'no_urs' => $noUrs,
    //                 ];
    //             })->toArray();
    //             $set('details', $details);
    //         });
    // }
    protected static function selectSpecInput(): Select
    {
        return Select::make('spesifikasi_product_id')
            ->label('Nama Customer')
            ->placeholder('Pilih Nama Customer')
            ->reactive()
            ->required()
            ->options(function () {
                return Cache::rememberForever(SpesifikasiProduct::$CACHE_KEY_SELECT, function () {
                    return SpesifikasiProduct::with('urs.customer')
                        ->whereDoesntHave('spk')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            $noUrs = $item->urs->no_urs ?? '-';
                            $customerName = $item->urs->customer->name ?? '-';
                            return [$item->id => "{$noUrs} - {$customerName}"];
                        });
                });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'details.product'])->find($state);
                if (!$spesifikasi) return;

                $noUrs = $spesifikasi->urs?->no_urs ?? '-';
                $details = $spesifikasi->details->map(function ($detail) use ($noUrs) {
                    return [
                        'name' => $detail->product?->name ?? '-',
                        'quantity' => $detail?->quantity ?? '-',
                        'no_urs' => $noUrs,
                    ];
                })->toArray();

                $set('details', $details);
            });
    }
}
