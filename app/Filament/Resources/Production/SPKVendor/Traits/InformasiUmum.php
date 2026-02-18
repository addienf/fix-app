<?php

namespace App\Filament\Resources\Production\SPKVendor\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

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

                self::select(),

                self::textInput('nama_perusahaan', 'Nama Perusahaan')

            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
            ]);
    }

    private static function select(): Select
    {
        return
            Select::make('perencanaan_id')
            ->label('Nomor SPK')
            ->placeholder('Pilih Nomor SPK')
            ->searchable()
            ->native(false)
            ->lazy()
            ->preload()
            ->required()
            ->options(function () {
                return JadwalProduksi::with([
                    'spk',
                    'identifikasiProduks'
                ])
                    // ->whereDoesntHave('spkVendor')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($perencanaanProduksi) {

                        $spkNo = $perencanaanProduksi->spk->no_spk ?? '-';
                        $noSeri = $perencanaanProduksi->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $perencanaanProduksi->id => "{$spkNo} - {$noSeri}",
                        ];
                    });
            })
            // ->getSearchResultsUsing(function (string $search) {

            //     if ($search === '') {
            //         return [];
            //     }

            //     return JadwalProduksi::with([
            //         'spk',
            //         'identifikasiProduks'
            //     ])
            //         ->whereDoesntHave('spkVendor')
            //         ->where(function ($query) use ($search) {
            //             $query->whereHas(
            //                 'jadwalProduksi',
            //                 fn($q) =>
            //                 $q->where('no_surat', 'LIKE', "%{$search}%")
            //             )
            //                 ->orWhereHas(
            //                     'spk',
            //                     fn($q) =>
            //                     $q->where('no_spk', 'LIKE', "%{$search}%")
            //                 )
            //                 ->orWhereHas(
            //                     'identifikasiProduks',
            //                     fn($q) =>
            //                     $q->where('no_seri', 'LIKE', "%{$search}%")
            //                 );
            //         })
            //         ->latest()
            //         ->limit(20)
            //         ->get()
            //         ->mapWithKeys(function ($permintaan) {

            //             $jadwal = $permintaan->jadwalProduksi;
            //             $spkNo = $jadwal->spk->no_spk ?? '-';
            //             $noSurat = $jadwal->no_surat ?? '-';
            //             $noSeri = $jadwal->identifikasiProduks
            //                 ->pluck('no_seri')
            //                 ->filter()
            //                 ->implode(', ') ?: '-';

            //             return [
            //                 $permintaan->id => "{$noSurat} - {$spkNo} - {$noSeri}",
            //             ];
            //         })
            //         ->toArray();
            // })
            ->getOptionLabelUsing(function ($value) {

                $jadwal = JadwalProduksi::with([
                    'spk',
                    'identifikasiProduks'
                ])
                    ->find($value);

                if (!$jadwal) return '-';

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

                $jadwal = JadwalProduksi::with([
                    'spk',
                    'identifikasiProduks'
                ])
                    ->find($state);

                if (!$jadwal) return;

                $company = $jadwal?->spk?->spesifikasiProduct?->urs?->company?->name ?? '-';

                $details = $jadwal->sumbers->map(fn($d) => [
                    'bahan_baku' => $d->bahan_baku,
                    'spesifikasi' => $d->spesifikasi,
                    'jumlah' => $d->jumlah,
                    'keperluan_barang' => $d->keperluan_barang,
                ])->toArray();

                // $set('nama_perusahaan', $company);
                // $set('details', $details);
            });
    }
}
