<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\Traits;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Validation\Rule;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection($form): Section
    {
        // $lastValue = PermintaanBahan::latest('no_surat')->value('no_surat');
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                self::getIsStock()
                    ->hiddenOn('edit'),

                self::select()
                    ->hidden(
                        fn($get, $livewire) =>
                        $get('is_stock') != 1 ||
                            $livewire instanceof \Filament\Resources\Pages\EditRecord
                    ),
                // ->hiddenOn('edit'),

                Grid::make($isEdit ? 3 : 2)
                    ->schema([

                        self::autoNumberField2('no_surat', 'No Surat', [
                            'prefix' => 'QKS',
                            'section' => 'WBB',
                            'type' => 'PERMINTAAN',
                            'table' => 'permintaan_bahans',
                        ])
                            ->rules(function (callable $get) {
                                return $get('is_stock') == 0
                                    ? ['nullable']
                                    : ['required', Rule::unique('permintaan_bahans', 'no_surat')];
                            })
                            ->hiddenOn('edit'),
                        // ->hidden(fn($get) => $get('is_stock') != 1),

                        self::dateInput('tanggal', 'Tanggal')
                            ->required(),

                        self::textInput('dari', 'Dari')
                            ->placeholder('Warehouse'),

                        self::textInput('kepada', 'Kepada')
                            ->placeholder('Purchasing'),

                    ])
            ]);
    }

    protected static function select(): Select
    {
        return
            // Select::make('permintaan_bahan_pro_id')
            // ->relationship(
            //     'permintaanBahanPro',
            //     'no_surat',
            //     fn($query) => $query->whereIn('id', Cache::rememberForever(
            //         PermintaanAlatDanBahan::$CACHE_KEYS['permintaanBahanWBB'],
            //         fn() => PermintaanAlatDanBahan::where('status_penyerahan', 'Diserahkan')
            //             ->where('status', 'Tidak Tersedia')
            //             ->whereDoesntHave('permintaanBahanWBB')
            //             ->pluck('id')
            //             ->toArray()
            //     ))
            // )
            Select::make('permintaan_bahan_pro_id')
            ->label('No Surat')
            ->searchable()
            ->options(function () {
                return PermintaanAlatDanBahan::query()
                    ->where('status_penyerahan', 'Diserahkan')
                    ->where('status', 'Tidak Tersedia')
                    ->whereDoesntHave('permintaanBahanWBB')
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_surat', 'id');
            })
            ->getSearchResultsUsing(function (string $search) {
                return PermintaanAlatDanBahan::query()
                    ->where('status_penyerahan', 'Diserahkan')
                    ->where('status', 'Tidak Tersedia')
                    ->whereDoesntHave('permintaanBahanWBB')
                    ->where('no_surat', 'like', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_surat', 'id');
            })
            ->label('Nomor Surat')
            ->placeholder('Pilih No Surat Dari Permintaan Alat dan Bahan Produksi')
            ->columnSpanFull()
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pab = PermintaanAlatDanBahan::with('details')->find($state);
                if (!$pab) return;

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

    private static function getIsStock()
    {
        return
            ButtonGroup::make('is_stock')
            ->label('')
            ->required()
            ->options([
                1 => 'Permintaan Biasa',
                0 => 'Untuk Stock',
            ])
            ->reactive()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->afterStateUpdated(function ($state, callable $set) {
                if ($state == 0) {
                    $kode = now()->format('YmdHis');
                    $set('no_surat', "Untuk Stock - {$kode}");
                    $set('dari', null);
                    $set('kepada', null);
                }
            });
    }
}
