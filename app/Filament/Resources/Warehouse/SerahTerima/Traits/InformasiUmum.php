<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\Traits;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
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
                        self::select(),

                        self::autoNumberField2('no_surat', 'No Surat', [
                            'prefix' => 'QKS',
                            'section' => 'WBB',
                            'type' => 'SERAHTERIMA',
                            'table' => 'serah_terima_bahans',
                        ]),

                        self::dateInput('tanggal', 'Tanggal')
                            ->required(),

                        self::textInput('dari', 'Dari')
                            ->placeholder('Warehouse'),

                        self::textInput('kepada', 'Kepada'),
                    ])
            ]);
    }

    protected static function select(): Select
    {
        return Select::make('permintaan_bahan_pro_id')
            ->relationship(
                'permintaanBahanPro',
                'no_surat',
                fn($query) => $query->whereIn('id', Cache::rememberForever(
                    PermintaanAlatDanBahan::$CACHE_KEYS['serahTerimaBahan'],
                    fn() => PermintaanAlatDanBahan::where('status_penyerahan', 'Diserahkan')
                        ->where('status', 'Tersedia')
                        ->whereDoesntHave('serahTerimaBahan')
                        ->pluck('id')
                        ->toArray()
                ))
            )
            ->label('Nomor Surat')
            ->placeholder('Pilih No Surat Dari Permintaan Alat dan Bahan Produksi')
            ->columnSpanFull()
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $pab = PermintaanAlatDanBahan::with('details')->find($state);

                if (!$pab)
                    return;

                $detailBahan = $pab->details?->map(function ($detail) {
                    return [
                        'bahan_baku' => $detail->bahan_baku ?? '',
                        'spesifikasi' => $detail->spesifikasi ?? '',
                        'jumlah' => $detail->jumlah ?? 0,
                        'keperluan_barang' => $detail->keperluan_barang ?? '',
                    ];
                })->toArray();

                $set('details', $detailBahan);
            });
    }
}
