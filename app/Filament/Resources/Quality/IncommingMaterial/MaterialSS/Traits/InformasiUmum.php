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
    protected static function informasiUmumSection(): Section
    {
        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make(2)
                    ->schema([
                        self::select('permintaan_pembelian_id', 'Permintaan Pembelian', 'permintaanPembelian', 'id')
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

    protected static function select(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
            ->relationship($relation, $title)
            ->options(function () {
                return Cache::rememberForever(PermintaanPembelian::$CACHE_KEYS['materialSS'], function () {
                    return
                        PermintaanPembelian::with('permintaanBahanWBB')
                        ->whereDoesntHave('materialSS')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->id => $item->permintaanBahanWBB->no_surat ?? 'Tanpa No Surat'];
                        });
                });
            })
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
    }
}
