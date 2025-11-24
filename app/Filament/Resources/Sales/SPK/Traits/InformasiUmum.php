<?php

namespace App\Filament\Resources\Sales\SPK\Traits;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

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

    private static function selectSpecInput(): Select
    {
        return
            Select::make('spesifikasi_product_id')
            ->label('Nama Customer')
            ->placeholder('Pilih Nama Customer')
            ->searchable()
            ->reactive()
            ->required()
            // ->options(function () {
            //     return Cache::rememberForever(
            //         SpesifikasiProduct::$CACHE_KEYS['select_spesifikasi'],
            //         function () {
            //             return SpesifikasiProduct::query()
            //                 ->select(['id', 'urs_id'])
            //                 ->with([
            //                     'urs' => fn($q) => $q->select(['id', 'no_urs', 'customer_id'])
            //                         ->with([
            //                             'customer' => fn($c) => $c->select(['id', 'name'])
            //                         ])
            //                 ])
            //                 ->whereDoesntHave('spk')
            //                 ->orderBy('id', 'desc')
            //                 ->limit(10)
            //                 ->get()
            //                 ->mapWithKeys(function ($item) {
            //                     $noUrs = $item->urs->no_urs ?? '-';
            //                     $customerName = $item->urs->customer->name ?? '-';
            //                     return [$item->id => "{$noUrs} - {$customerName}"];
            //                 });
            //         }
            //     );
            // })
            ->options(function () {
                return SpesifikasiProduct::query()
                    ->select(['id', 'urs_id'])
                    ->with([
                        'urs' => fn($q) => $q->select(['id', 'no_urs', 'customer_id'])
                            ->with([
                                'customer' => fn($c) => $c->select(['id', 'name'])
                            ])
                    ])
                    ->whereDoesntHave('spk')
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $noUrs = $item->urs->no_urs ?? '-';
                        $customerName = $item->urs->customer->name ?? '-';
                        return [$item->id => "{$noUrs} - {$customerName}"];
                    });
            })
            // ->getSearchResultsUsing(function (string $search) {
            //     if ($search === '') {
            //         return Cache::get(SpesifikasiProduct::$CACHE_PREFIXES['search_spesifikasi'], []);
            //     }
            //     return SpesifikasiProduct::query()
            //         ->select(['id', 'urs_id'])
            //         ->with([
            //             'urs' => fn($q) => $q->select(['id', 'no_urs', 'customer_id'])
            //                 ->with([
            //                     'customer' => fn($c) => $c->select(['id', 'name'])
            //                 ])
            //         ])
            //         ->whereDoesntHave('spk')
            //         ->whereHas('urs.customer', function ($q) use ($search) {
            //             $q->where('name', 'LIKE', "%{$search}%")
            //                 ->orWhere('no_urs', 'LIKE', "%{$search}%");
            //         })
            //         ->limit(20)
            //         ->get()
            //         ->mapWithKeys(function ($item) {
            //             $noUrs = $item->urs->no_urs ?? '-';
            //             $customerName = $item->urs->customer->name ?? '-';
            //             return [$item->id => "{$noUrs} - {$customerName}"];
            //         });
            // })
            ->getSearchResultsUsing(function (string $search) {
                return SpesifikasiProduct::query()
                    ->select(['id', 'urs_id'])
                    ->with([
                        'urs' => fn($q) => $q->select(['id', 'no_urs', 'customer_id'])
                            ->with([
                                'customer' => fn($c) => $c->select(['id', 'name'])
                            ])
                    ])
                    ->whereDoesntHave('spk')
                    ->whereHas('urs.customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('no_urs', 'LIKE', "%{$search}%");
                    })
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $noUrs = $item->urs->no_urs ?? '-';
                        $customerName = $item->urs->customer->name ?? '-';
                        return [$item->id => "{$noUrs} - {$customerName}"];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spesifikasi = SpesifikasiProduct::select(['id', 'urs_id'])
                    ->with([
                        'urs' => fn($q) => $q->select(['id', 'no_urs']),
                        'details' => fn($q) => $q->select(['id', 'spesifikasi_product_id', 'product_id', 'quantity'])
                            ->with([
                                'product' => fn($p) => $p->select(['id', 'name'])
                            ])
                    ])
                    ->find($state);

                if (!$spesifikasi) return;

                $noUrs = $spesifikasi->urs?->no_urs ?? '-';

                $details = $spesifikasi->details->map(function ($detail) use ($noUrs) {
                    return [
                        'name'     => $detail->product?->name ?? '-',
                        'quantity' => $detail->quantity ?? '-',
                        'no_urs'   => $noUrs,
                    ];
                })->toArray();

                $set('details', $details);
            });
    }
}
