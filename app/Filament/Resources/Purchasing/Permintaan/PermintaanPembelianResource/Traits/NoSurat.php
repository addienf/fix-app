<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Traits;

use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait NoSurat
{
    use SimpleFormResource, HasAutoNumber;
    protected static function noSuratSection(): Section
    {
        return Section::make('Nomor Surat')
            ->hiddenOn('edit')
            ->collapsible()
            ->schema([

                static::select('permintaan_bahan_wbb_id', 'Pilih Nomor Surat', 'permintaanBahanWBB', 'no_surat')
                    ->placeholder('Pilin No Surat Dari Permintaan Bahan Pembelian')
                    ->columnSpanFull(),

            ]);
    }

    protected static function select2(): Select
    {
        return
            // Select::make('permintaan_bahan_wbb_id')
            // ->relationship(
            //     'permintaanBahanWBB',
            //     'no_surat',
            //     fn($query) => $query->whereIn('id', Cache::rememberForever(
            //         PermintaanBahan::$CACHE_KEYS['pembelian'],
            //         fn() => PermintaanBahan::whereDoesntHave('pembelian')
            //             ->pluck('id')
            //             ->toArray()
            //     ))
            // )
            Select::make('permintaan_bahan_wbb_id')
            ->label('No Surat')
            ->searchable()
            ->options(function () {
                return PermintaanBahan::query()
                    ->whereDoesntHave('pembelian')
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_surat', 'id');
            })
            ->getSearchResultsUsing(function (string $search) {
                return PermintaanBahan::query()
                    ->whereDoesntHave('pembelian')
                    ->where('no_surat', 'like', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_surat', 'id');
            })
            ->label('Pilih Nomor Surat')
            ->placeholder('Pilin No Surat Dari Permintaan Bahan Pembelian')
            ->columnSpanFull()
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $pab = PermintaanBahan::with('details')->find($state);

                if (!$pab)
                    return;

                $detailBahan = $pab->details?->map(function ($detail) {
                    return [
                        'nama_barang' => $detail->bahan_baku ?? '',
                        'jumlah' => $detail->jumlah ?? 0,
                    ];
                })->toArray();

                $set('details', $detailBahan);
            });
    }

    protected static function select(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
            ->relationship(
                $relation,
                $title,
                modifyQueryUsing: fn($query) => $query->whereDoesntHave('pembelian')
            )
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $pab = PermintaanBahan::with('details')->find($state);

                if (!$pab)
                    return;

                $detailBahan = $pab->details?->map(function ($detail) {
                    return [
                        'nama_barang' => $detail->bahan_baku ?? '',
                        'jumlah' => $detail->jumlah ?? 0,
                    ];
                })->toArray();

                $set('details', $detailBahan);
            })
        ;
    }
}
