<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
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

                Grid::make([
                    'default' => 1,
                    'md' => $isEdit ? 3 : 2,
                    'lg' => $isEdit ? 3 : 2,
                ])
                    ->schema([

                        // self::autoNumberField2('no_surat', 'No Surat', [
                        //     'prefix' => 'QKS',
                        //     'section' => 'WBB',
                        //     'type' => 'PERMINTAAN',
                        //     'table' => 'permintaan_bahans',
                        // ])
                        //     ->rules(function (callable $get) {
                        //         return $get('is_stock') == 0
                        //             ? ['nullable']
                        //             : ['required', Rule::unique('permintaan_bahans', 'no_surat')];
                        //     })
                        //     ->hiddenOn('edit'),
                        self::autoNumberField3('no_surat', 'No Surat')
                            ->hiddenOn('edit'),

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
            Select::make('perencanaan_id')
            ->label('No Surat Perencanaan Produksi')
            ->searchable()
            ->options(function () {
                return JadwalProduksi::query()
                    ->where('status_persetujuan', 'Disetujui')
                    ->whereDoesntHave('permintaanBahanWBB')
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_surat', 'id');
            })
            ->getSearchResultsUsing(function (string $search) {
                return JadwalProduksi::query()
                    ->where('status_persetujuan', 'Disetujui')
                    ->whereDoesntHave('permintaanBahanWBB')
                    ->where('no_surat', 'like', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_surat', 'id');
            })
            ->label('Nomor Surat')
            ->placeholder('Pilih No Surat Dari Perencanaan Produksi')
            ->columnSpanFull()
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pab = JadwalProduksi::with('sumbers')->find($state);
                if (!$pab) return;

                $detailBahan = $pab->sumbers?->map(function ($detail) {
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
        return ButtonGroup::make('is_stock')
            ->options([
                1 => 'Permintaan Biasa',
                0 => 'Untuk Stock',
            ])
            ->reactive()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->afterStateHydrated(function (callable $set) {
                $set(
                    'no_surat',
                    self::generateNoSurat2(
                        'permintaan_bahans',
                        'no_surat'
                    )
                );
            })
            ->afterStateUpdated(function ($state, callable $set) {
                $set(
                    'no_surat',
                    self::generateNoSurat2(
                        'permintaan_bahans',
                        'no_surat'
                    )
                );

                if ($state == 0) {
                    $set('dari', null);
                    $set('kepada', null);
                }
            });
    }
}
