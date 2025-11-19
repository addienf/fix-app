<?php

namespace App\Filament\Resources\Production\SPKVendor\Traits;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Sales\URS;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
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
        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                self::autoNumberField2('no_spk_vendor', 'Nomor SPK Vendor', [
                    'prefix' => 'QKS',
                    'section' => 'PRO',
                    'type' => 'SPK',
                    'table' => 'spk_vendors',
                ])
                    ->columnSpanFull(),
                // ->hiddenOn('edit')
                // ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia'),

                self::select(),

                self::textInput('nama_perusahaan', 'Nama Perusahaan')

            ])
            ->columns(2);
    }

    private static function select(): Select
    {
        return
            Select::make('permintaan_bahan_pro_id')
            ->label('Nomor SPK')
            ->placeholder('Pilih Nomor SPK')
            ->searchable()
            ->native(false)
            ->lazy()
            ->preload()
            ->required()
            ->options(function () {
                return PermintaanAlatDanBahan::with([
                    'jadwalProduksi.spk',
                    'jadwalProduksi.identifikasiProduks'
                ])
                    ->whereDoesntHave('spkVendor')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($permintaan) {

                        $jadwal = $permintaan->jadwalProduksi;
                        $spkNo = $jadwal->spk->no_spk ?? '-';
                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $permintaan->id => "{$spkNo} - {$noSeri}",
                        ];
                    });
            })
            ->getSearchResultsUsing(function (string $search) {

                if ($search === '') {
                    return [];
                }

                return PermintaanAlatDanBahan::with([
                    'jadwalProduksi.spk',
                    'jadwalProduksi.identifikasiProduks'
                ])
                    ->whereDoesntHave('spkVendor')
                    ->where(function ($query) use ($search) {
                        $query->whereHas(
                            'jadwalProduksi',
                            fn($q) =>
                            $q->where('no_surat', 'LIKE', "%{$search}%")
                        )
                            ->orWhereHas(
                                'jadwalProduksi.spk',
                                fn($q) =>
                                $q->where('no_spk', 'LIKE', "%{$search}%")
                            )
                            ->orWhereHas(
                                'jadwalProduksi.identifikasiProduks',
                                fn($q) =>
                                $q->where('no_seri', 'LIKE', "%{$search}%")
                            );
                    })
                    ->latest()
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(function ($permintaan) {

                        $jadwal = $permintaan->jadwalProduksi;
                        $spkNo = $jadwal->spk->no_spk ?? '-';
                        $noSurat = $jadwal->no_surat ?? '-';
                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $permintaan->id => "{$noSurat} - {$spkNo} - {$noSeri}",
                        ];
                    })
                    ->toArray();
            })
            ->getOptionLabelUsing(function ($value) {

                $permintaan = PermintaanAlatDanBahan::with([
                    'jadwalProduksi.spk',
                    'jadwalProduksi.identifikasiProduks'
                ])
                    ->find($value);

                if (!$permintaan) return '-';

                $jadwal = $permintaan->jadwalProduksi;
                $spkNo = $jadwal->spk->no_spk ?? '-';
                $noSurat = $jadwal->no_surat ?? '-';
                $noSeri = $jadwal->identifikasiProduks
                    ->pluck('no_seri')
                    ->filter()
                    ->implode(', ') ?: '-';

                return "{$noSurat} - {$spkNo} - {$noSeri}";
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;
                $permintaan = PermintaanAlatDanBahan::with([
                    'jadwalProduksi:id,spk_marketing_id',
                    'jadwalProduksi.spk:id,spesifikasi_product_id',
                    'jadwalProduksi.spk.spesifikasiProduct:id,urs_id',
                    'jadwalProduksi.spk.spesifikasiProduct.urs:id,customer_id',
                    'jadwalProduksi.spk.spesifikasiProduct.urs.customer:id,company_name',
                    'jadwalProduksi.sumbers:id,jadwal_produksi_id,bahan_baku,spesifikasi,jumlah,keperluan'
                ])->find($state);

                if (!$permintaan) return;

                $company = optional(
                    $permintaan->jadwalProduksi?->spk?->spesifikasiProduct?->urs?->customer
                )->company_name ?? '-';

                $details = $permintaan->details->map(fn($d) => [
                    'bahan_baku' => $d->bahan_baku,
                    'spesifikasi' => $d->spesifikasi,
                    'jumlah' => $d->jumlah,
                    'keperluan_barang' => $d->keperluan_barang,
                ])->toArray();

                $set('nama_perusahaan', $company);
                $set('details', $details);
            });
    }
}
