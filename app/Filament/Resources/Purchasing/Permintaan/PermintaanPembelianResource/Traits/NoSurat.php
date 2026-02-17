<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Traits;

use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait NoSurat
{
    use SimpleFormResource, HasAutoNumber;
    protected static function noSuratSection(): Section
    {
        return
            Section::make('Informasi Umum')
            ->hiddenOn('edit')
            ->collapsible()
            ->schema([

                static::getIsStock()
                    ->hiddenOn('edit'),

                // static::select3()
                //     ->hidden(
                //         fn($get, $livewire) =>
                //         $get('is_stock') != 1 ||
                //             $livewire instanceof \Filament\Resources\Pages\EditRecord
                //     )
                //     ->columnSpanFull()
                // static::getIsStock(),

                static::select3()
                    ->hidden(
                        fn($get, $livewire) =>
                        $get('is_stock') != 1 &&
                            $get('is_stock') != 0
                    )
                    ->columnSpanFull(),
            ]);
    }

    // private static function select2(): Select
    // {
    //     return
    //         Select::make('permintaan_bahan_wbb_id')
    //         ->label('No Surat')
    //         ->searchable()
    //         ->options(function () {
    //             return PermintaanBahan::query()
    //                 ->whereDoesntHave('pembelian')
    //                 ->where('is_stock', '!=', 0)
    //                 ->orderBy('id', 'desc')
    //                 ->limit(10)
    //                 ->pluck('no_surat', 'id');
    //         })
    //         ->getSearchResultsUsing(function (string $search) {
    //             return PermintaanBahan::query()
    //                 ->whereDoesntHave('pembelian')
    //                 ->where('is_stock', '!=', 0)
    //                 ->where('no_surat', 'like', "%{$search}%")
    //                 ->orderBy('id', 'desc')
    //                 ->limit(10)
    //                 ->pluck('no_surat', 'id');
    //         })
    //         ->label('Pilih Nomor Surat')
    //         ->placeholder('Pilin No Surat Dari Permintaan Bahan Pembelian')
    //         ->columnSpanFull()
    //         ->native(false)
    //         ->searchable()
    //         ->preload()
    //         ->required()
    //         ->reactive()
    //         ->afterStateUpdated(function ($state, callable $set) {
    //             if (!$state)
    //                 return;

    //             $pab = PermintaanBahan::with([
    //                 'permintaanDetails' => function ($query) {
    //                     $query->where('status_stock', 'Tidak Tersedia');
    //                 }
    //             ])->find($state);

    //             if (!$pab)
    //                 return;

    //             $detailBahan = $pab->permintaanDetails?->map(function ($detail) {
    //                 return [
    //                     'nama_barang' => $detail->bahan_baku ?? '',
    //                     'jumlah' => $detail->jumlah ?? 0,
    //                 ];
    //             })->toArray();

    //             $set('details', $detailBahan);
    //         });
    // }

    // protected static function select(string $fieldName, string $label, string $relation, string $title): Select
    // {
    //     return
    //         Select::make($fieldName)
    //         ->relationship(
    //             $relation,
    //             $title,
    //             modifyQueryUsing: fn($query) => $query->whereDoesntHave('pembelian')
    //         )
    //         ->label($label)
    //         ->native(false)
    //         ->searchable()
    //         ->preload()
    //         ->required()
    //         ->reactive()
    //         ->afterStateUpdated(function ($state, callable $set) {
    //             if (!$state)
    //                 return;

    //             $pab = PermintaanBahan::with('details')->find($state);

    //             if (!$pab)
    //                 return;

    //             $detailBahan = $pab->details?->map(function ($detail) {
    //                 return [
    //                     'nama_barang' => $detail->bahan_baku ?? '',
    //                     'jumlah' => $detail->jumlah ?? 0,
    //                 ];
    //             })->toArray();

    //             $set('details', $detailBahan);
    //         })
    //     ;
    // }

    private static function select3(): Select
    {
        return
            Select::make('permintaan_bahan_wbb_id')
            ->label('Pilih Nomor Surat')
            ->placeholder('Pilih No Surat Dari Permintaan Bahan')
            ->native(false)
            ->searchable()
            ->preload()
            ->reactive()
            ->required(fn(callable $get) => $get('is_stock') == 1)
            ->options(function (callable $get) {

                $isStock = $get('is_stock') ?? 1;

                return PermintaanBahan::query()
                    ->whereDoesntHave('pembelian')
                    ->whereHas('permintaanDetails', function ($q) {
                        $q->where('status_stock', 'Tidak Tersedia');
                    })
                    ->when(
                        $isStock == 0,
                        fn($q) => $q->where('is_stock', 0)
                    )
                    ->when(
                        $isStock == 1,
                        fn($q) => $q->where('is_stock', '!=', 0)
                    )
                    ->orderByDesc('id')
                    ->limit(10)
                    ->pluck('no_surat', 'id');
            })
            // ->options(function (callable $get) {

            //     $isStock = $get('is_stock') ?? 1;

            //     return PermintaanBahan::query()
            //         ->whereDoesntHave('pembelian')
            //         ->when(
            //             $isStock == 0,
            //             fn($q) =>
            //             $q->where('is_stock', 0)
            //         )
            //         ->when(
            //             $isStock == 1,
            //             fn($q) =>
            //             $q->where('is_stock', '!=', 0)
            //         )
            //         ->orderByDesc('id')
            //         ->limit(10)
            //         ->pluck('no_surat', 'id');
            // })

            // ->getSearchResultsUsing(function (string $search, callable $get) {

            //     $isStock = $get('is_stock') ?? 1;

            //     return PermintaanBahan::query()
            //         ->whereDoesntHave('pembelian')
            //         ->where('no_surat', 'like', "%{$search}%")
            //         ->when(
            //             $isStock == 0,
            //             fn($q) =>
            //             $q->where('is_stock', 0)
            //         )
            //         ->when(
            //             $isStock == 1,
            //             fn($q) =>
            //             $q->where('is_stock', '!=', 0)
            //         )
            //         ->orderByDesc('id')
            //         ->limit(10)
            //         ->pluck('no_surat', 'id');
            // })

            ->afterStateUpdated(function ($state, callable $set) {

                if (!$state) {
                    $set('details', []);
                    return;
                }

                $pab = PermintaanBahan::with([
                    'permintaanDetails' => function ($query) {
                        $query->where('status_stock', 'Tidak Tersedia');
                    }
                ])->find($state);

                if (!$pab) return;

                $detailBahan = $pab->permintaanDetails?->map(function ($detail) {
                    return [
                        'nama_barang' => $detail->bahan_baku ?? '',
                        'jumlah' => $detail->jumlah ?? 0,
                    ];
                })->toArray();

                $set('details', $detailBahan);
            });
    }

    private static function getIsStock()
    {
        return
            ButtonGroup::make('is_stock')
            ->options([
                1 => 'Permintaan Biasa',
                0 => 'Untuk Stock',
            ])
            ->required()
            ->reactive()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->afterStateUpdated(function ($state, callable $set) {
                $set('permintaan_bahan_wbb_id', null);
                $set('details', []);
            });
    }
}
