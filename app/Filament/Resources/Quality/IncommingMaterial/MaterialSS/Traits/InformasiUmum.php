<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make($isEdit ? 3 : 2)
                    ->schema([
                        self::select2()
                            ->placeholder('Pilih Nomor Permintaan Pembelian')
                            ->hiddenOn('edit')
                            ->required(),

                        self::textInput('no_qc', 'No. QC SS'),
                        // ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                        // ->hint('Format: XXX/QKS/WBB/PERMINTAAN/MM/YY'),

                        self::textInput('no_po', 'No. PO'),
                        // ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                        // ->hint('Format: XXX/QKS/WBB/PERMINTAAN/MM/YY'),

                        self::textInput('supplier', 'Supplier'),
                    ])

            ]);
    }

    protected static function remarksSection(): Section
    {
        return Section::make('Catatan')
            ->collapsible()
            ->schema([
                self::textareaInput('remarks', 'Remarks')
            ]);
    }

    // protected static function select(string $fieldName, string $label, string $relation, string $title): Select
    // {
    //     return
    //         Select::make($fieldName)
    //         ->relationship($relation, $title)
    //         ->options(function () {
    //             return Cache::rememberForever(PermintaanPembelian::$CACHE_KEYS['materialSS'], function () {
    //                 return
    //                     PermintaanPembelian::with('permintaanBahanWBB')
    //                     ->whereDoesntHave('materialSS')
    //                     ->get()
    //                     ->mapWithKeys(function ($item) {
    //                         return [$item->id => $item->permintaanBahanWBB->no_surat ?? 'Tanpa No Surat'];
    //                     });
    //             });
    //         })
    //         ->label($label)
    //         ->native(false)
    //         ->searchable()
    //         ->preload()
    //         ->required()
    //         ->reactive();
    // }

    private static function select2(): Select
    {
        return
            // Select::make($fieldName)
            // ->relationship($relation, $title)
            // ->options(function () {
            //     return Cache::rememberForever(PermintaanPembelian::$CACHE_KEYS['materialNonSS'], function () {
            //         return
            //             PermintaanPembelian::with('permintaanBahanWBB')
            //             ->whereDoesntHave('materialNonSS')
            //             ->get()
            //             ->mapWithKeys(function ($item) {
            //                 return [$item->id => $item->permintaanBahanWBB->no_surat ?? 'Tanpa No Surat'];
            //             });
            //     });
            // })
            Select::make('permintaan_pembelian_id')
            ->label('Permintaan Pembelian')
            ->placeholder('Pilih Nomor Permintaan Pembelian')
            ->searchable()
            ->hiddenOn('edit')
            ->required()
            ->options(function () {
                return PermintaanPembelian::query()
                    ->with('permintaanBahanWBB')
                    ->whereDoesntHave('materialSS')
                    ->where('is_stock', '=', 1)
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id => $item->permintaanBahanWBB->no_surat ?? 'Tanpa No Surat',
                        ];
                    });
            })
            ->getSearchResultsUsing(function (string $search) {
                return PermintaanPembelian::query()
                    ->with('permintaanBahanWBB')
                    ->whereDoesntHave('materialNonSS')
                    ->whereHas(
                        'permintaanBahanWBB',
                        fn($q) =>
                        $q->where('no_surat', 'like', "%{$search}%")
                    )
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id => $item->permintaanBahanWBB->no_surat ?? 'Tanpa No Surat',
                        ];
                    });
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
    }
}
