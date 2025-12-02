<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

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

                        self::textInput('no_po', 'No. PO'),

                        self::textInput('supplier', 'Supplier'),
                    ])

            ]);
    }

    protected static function noBatchSection(): Section
    {
        return Section::make('Nomor Batch')
            ->collapsible()
            ->schema([

                self::textInput('batch_no', 'Batch No')

            ]);
    }

    private static function select2(): Select
    {
        return
            Select::make('permintaan_pembelian_id')
            ->label('Permintaan Pembelian')
            ->placeholder('Pilih Nomor Permintaan Pembelian')
            ->searchable()
            ->hiddenOn('edit')
            ->required()
            ->options(function () {
                return PermintaanPembelian::query()
                    ->with('permintaanBahanWBB')
                    ->whereDoesntHave('materialNonSS')
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

    // protected static function selectSpecInput(): Select
    // {
    //     return Select::make('spesifikasi_product_id')
    //         ->label('Nama Customer')
    //         ->placeholder('Pilih Nama Customer')
    //         ->reactive()
    //         ->required()
    //         ->options(function () {
    //             return Cache::rememberForever(SpesifikasiProduct::$CACHE_KEY_SELECT, function () {
    //                 return SpesifikasiProduct::with('urs.customer')
    //                     ->whereDoesntHave('spk')
    //                     ->get()
    //                     ->mapWithKeys(function ($item) {
    //                         $noUrs = $item->urs->no_urs ?? '-';
    //                         $customerName = $item->urs->customer->name ?? '-';
    //                         return [$item->id => "{$noUrs} - {$customerName}"];
    //                     });
    //             });
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
}
