<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\Traits;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection(): Section
    {
        $lastValue = PermintaanAlatDanBahan::latest('no_surat')->value('no_surat');

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([
                        self::selectInputPermintaanBahan()
                            ->placeholder('Pilih Nomor SPK')
                            ->label('No SPK')
                            ->hiddenOn('edit')
                            ->columnSpanFull(),

                        // self::textInput('no_surat', 'No Surat')
                        //     ->unique(ignoreRecord: true)
                        //     ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                        //     ->hint('Format: XXX/QKS/PRO/PERMINTAAN/MM/YY'),
                        self::autoNumberField2('no_surat', 'No Surat', [
                            'prefix' => 'QKS',
                            'section' => 'PRO',
                            'type' => 'PERMINTAAN',
                            'table' => 'permintaan_alat_dan_bahans',
                        ])
                            ->hiddenOn('edit')
                            ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia'),

                        self::dateInput('tanggal', 'Tanggal'),

                        self::textInput('dari', 'Dari')
                            ->placeholder('Produksi')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('kepada', 'Kepada')
                            ->placeholder('Warehouse')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::buttonGroup('status', 'Status Stock Barang')
                            ->columnSpanFull()
                            ->hiddenOn(operations: 'create'),
                    ])
            ]);
    }

    protected static function selectInputPermintaanBahan(): Select
    {
        return
            Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            ->label('spk')
            ->options(function () {
                return Cache::rememberForever(SPKMarketing::$CACHE_KEYS['permintaan_bahan'], function () {
                    return SPKMarketing::whereHas('jadwalProduksi', function ($query) {
                        $query->where('status_persetujuan', 'Disetujui');
                    })
                        ->whereDoesntHave('permintaan')
                        ->pluck('no_spk', 'id');
                });
            })
            // ->options(function () {
            //     return Cache::rememberForever(SpesifikasiProduct::$CACHE_KEY_SELECT, function () {
            //         return SpesifikasiProduct::with('urs.customer')
            //             ->whereDoesntHave('spk')
            //             ->get()
            //             ->mapWithKeys(function ($item) {
            //                 $noUrs = $item->urs->no_urs ?? '-';
            //                 $customerName = $item->urs->customer->name ?? '-';
            //                 return [$item->id => "{$noUrs} - {$customerName}"];
            //             });
            //     });
            // })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $spk = SPKMarketing::with('jadwalProduksi.sumbers')->find($state);
                if (!$spk) return;

                $set('dari', $spk->dari);
                $set('kepada', $spk->kepada);

                $jadwal = $spk->jadwalProduksi;

                if ($jadwal && $jadwal->sumbers) {
                    $sumbers = $jadwal->sumbers->map(function ($sumber) {
                        return [
                            'bahan_baku' => $sumber->bahan_baku ?? '-',
                            'spesifikasi' => $sumber->spesifikasi ?? '-',
                            'jumlah' => $sumber->jumlah ?? '-',
                            'keperluan_barang' => $sumber->keperluan ?? '-',
                        ];
                    })->toArray();

                    $set('details', $sumbers);
                }
            });
    }

    protected static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                'Tersedia' => 'Tersedia',
                'Tidak Tersedia' => 'Tidak Tersedia',
            ])
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->default('individual');
    }
}
