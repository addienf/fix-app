<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
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
    protected static function informasiUmumSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';
        $lastValue = PermintaanAlatDanBahan::latest('no_surat')->value('no_surat');

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make($isEdit ? 3 : 2)
                    ->schema([
                        self::selectInputPermintaanBahan()
                            ->placeholder('Pilih Nomor Surat Perencanaan Produksi')
                            // ->label('No SPK')
                            ->hiddenOn('edit')
                            ->columnSpanFull(),

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

    // protected static function selectInputPermintaanBahan(): Select
    // {
    //     return
    //         Select::make('jadwal_id')
    //         ->label('Nomor Surat Perencanaan Produksi')
    //         ->searchable()
    //         ->native(false)
    //         ->lazy()
    //         ->preload()
    //         ->required()
    //         ->options(function () {
    //             return Cache::rememberForever(JadwalProduksi::$CACHE_KEYS['permintaanBahanProduksi'], function () {
    //                 return JadwalProduksi::with(['spk', 'identifikasiProduks'])
    //                     ->where('status_persetujuan', 'Disetujui')
    //                     ->whereDoesntHave('permintaanBahanProduksi')
    //                     ->latest()
    //                     ->take(10)
    //                     ->get()
    //                     ->mapWithKeys(function ($jadwal) {
    //                         $spkNo = $jadwal->spk->no_spk ?? '-';
    //                         $no_surat = $jadwal->no_surat ?? '-';
    //                         $noSeri = $jadwal->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-';
    //                         return [$jadwal->id => "{$no_surat} - {$spkNo} - {$noSeri}"];
    //                     });
    //             });
    //         })
    //         ->getSearchResultsUsing(function (string $search) {
    //             return JadwalProduksi::with(['spk', 'identifikasiProduks'])
    //                 ->where('status_persetujuan', 'Disetujui')
    //                 ->whereDoesntHave('permintaanBahanProduksi')
    //                 ->where(function ($query) use ($search) {
    //                     $query->where('no_surat', 'like', "%{$search}%")
    //                         ->orWhereHas('spk', fn($q) => $q->where('no_spk', 'like', "%{$search}%"))
    //                         ->orWhereHas('identifikasiProduks', fn($q) => $q->where('no_seri', 'like', "%{$search}%"));
    //                 })
    //                 ->latest()
    //                 ->limit(20)
    //                 ->get()
    //                 ->mapWithKeys(function ($jadwal) {
    //                     $spkNo = $jadwal->spk->no_spk ?? '-';
    //                     $no_surat = $jadwal->no_surat ?? '-';
    //                     $noSeri = $jadwal->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-';
    //                     return [$jadwal->id => "{$no_surat} - {$spkNo} - {$noSeri}"];
    //                 })
    //                 ->toArray();
    //         })
    //         ->getOptionLabelUsing(function ($value) {
    //             $jadwal = JadwalProduksi::with(['spk', 'identifikasiProduks'])->find($value);
    //             if (!$jadwal) return '-';
    //             $spkNo = $jadwal->spk->no_spk ?? '-';
    //             $no_surat = $jadwal->no_surat ?? '-';
    //             $noSeri = $jadwal->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-';
    //             return "{$no_surat} - {$spkNo} - {$noSeri}";
    //         })
    //         ->afterStateUpdated(function ($state, callable $set) {
    //             if (!$state)
    //                 return;

    //             $jadwal = JadwalProduksi::with('spk')->find($state);

    //             $set('dari', $jadwal->spk->dari);
    //             $set('kepada', $jadwal->spk->kepada);

    //             if ($jadwal) {
    //                 $sumbers = $jadwal->sumbers->map(function ($sumber) {
    //                     return [
    //                         'bahan_baku' => $sumber->bahan_baku ?? '-',
    //                         'spesifikasi' => $sumber->spesifikasi ?? '-',
    //                         'jumlah' => $sumber->jumlah ?? '-',
    //                         'keperluan_barang' => $sumber->keperluan ?? '-',
    //                     ];
    //                 })->toArray();
    //                 $set('details', $sumbers);
    //             }
    //         });
    // }
    protected static function selectInputPermintaanBahan(): Select
    {
        return
            Select::make('jadwal_id')
            ->label('Nomor Surat Perencanaan Produksi')
            ->placeholder('Pilih Nomor Surat')
            ->searchable()
            ->native(false)
            ->lazy()
            ->preload()
            ->required()
            ->options(function () {
                return Cache::rememberForever(JadwalProduksi::$CACHE_KEYS['select_jadwal'], function () {
                    return JadwalProduksi::with(['spk', 'identifikasiProduks'])
                        ->where('status_persetujuan', 'Disetujui')
                        ->whereDoesntHave('permintaanBahanProduksi')
                        ->latest()
                        ->limit(10)
                        ->get()
                        ->mapWithKeys(function ($jadwal) {
                            $spkNo = $jadwal->spk->no_spk ?? '-';
                            $noSurat = $jadwal->no_surat ?? '-';
                            $noSeri   = $jadwal->identifikasiProduks
                                ->pluck('no_seri')
                                ->filter()
                                ->implode(', ') ?: '-';

                            return [
                                $jadwal->id => "{$noSurat} - {$spkNo} - {$noSeri}"
                            ];
                        });
                });
            })
            ->getSearchResultsUsing(function (string $search) {
                if ($search === '') {
                    return Cache::get(JadwalProduksi::$CACHE_PREFIXES['search_jadwal'], []);
                }

                return JadwalProduksi::with(['spk', 'identifikasiProduks'])
                    ->where('status_persetujuan', 'Disetujui')
                    ->whereDoesntHave('permintaanBahanProduksi')
                    ->where(function ($query) use ($search) {
                        $query->where('no_surat', 'LIKE', "%{$search}%")
                            ->orWhereHas('spk', fn($q) => $q->where('no_spk', 'LIKE', "%{$search}%"))
                            ->orWhereHas('identifikasiProduks', fn($q) => $q->where('no_seri', 'LIKE', "%{$search}%"));
                    })
                    ->latest()
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(function ($jadwal) {
                        $spkNo = $jadwal->spk->no_spk ?? '-';
                        $noSurat = $jadwal->no_surat ?? '-';
                        $noSeri   = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $jadwal->id => "{$noSurat} - {$spkNo} - {$noSeri}"
                        ];
                    })
                    ->toArray();
            })
            ->getOptionLabelUsing(function ($value) {
                $jadwal = JadwalProduksi::with(['spk', 'identifikasiProduks'])->find($value);

                if (!$jadwal) return '-';

                $spkNo = $jadwal->spk->no_spk ?? '-';
                $noSurat = $jadwal->no_surat ?? '-';
                $noSeri   = $jadwal->identifikasiProduks
                    ->pluck('no_seri')
                    ->filter()
                    ->implode(', ') ?: '-';

                return "{$noSurat} - {$spkNo} - {$noSeri}";
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $jadwal = JadwalProduksi::with(['spk', 'sumbers'])->find($state);

                if (!$jadwal) return;

                $set('dari', $jadwal->spk->dari ?? '-');
                $set('kepada', $jadwal->spk->kepada ?? '-');

                $details = $jadwal->sumbers->map(function ($sumber) {
                    return [
                        'bahan_baku'       => $sumber->bahan_baku ?? '-',
                        'spesifikasi'      => $sumber->spesifikasi ?? '-',
                        'jumlah'           => $sumber->jumlah ?? '-',
                        'keperluan_barang' => $sumber->keperluan ?? '-',
                    ];
                })->toArray();

                $set('details', $details);
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
