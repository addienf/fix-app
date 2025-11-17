<?php

namespace App\Filament\Resources\Production\Jadwal\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\SimpleFormResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;

trait InformasiUmum
{
    use SimpleFormResource;
    protected static function informasiUmumSection(): Section
    {
        return Section::make('Informasi Umum')
            ->schema([

                Grid::make(2)
                    ->schema([

                        self::dateInput('tanggal', 'Tanggal')
                            ->required(),

                        self::textInput('pic_name', 'Penanggung Jawab'),

                        self::textInput('no_surat', 'No Surat'),

                        self::select('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                            ->hiddenOn('edit')
                            ->placeholder('Pilih Nomor SPK')
                    ])

            ]);
    }

    protected static function standardSection(): Section
    {
        return Section::make('Standard')
            ->schema([
                self::uploadField(
                    'file_upload',
                    'File Pendukung',
                    'Production/Jadwal/Files',
                    'Drawing wajib dilampirkan',
                    types: ['application/pdf'],
                    maxSize: 10240,
                ),
            ]);
    }

    protected static function select(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
            // ->relationship(
            //     $relation,
            //     $title,
            //     fn($query) => $query->where('status_penerimaan', 'Diterima')->whereDoesntHave('jadwalProduksi')
            // )
            ->relationship(
                'spk',
                'no_spk',
                fn($query) => $query
                    ->where('status_penerimaan', 'Diterima')
                    ->whereHas('spesifikasiProduct.details', function ($q) {
                        $q->whereRaw(
                            'quantity > (
                            SELECT COUNT(*)
                            FROM identifikasi_produks ip
                            JOIN jadwal_produksis jp ON jp.id = ip.jadwal_produksi_id
                            WHERE jp.spk_marketing_id = spk_marketings.id
                            AND ip.nama_alat = (SELECT name FROM products WHERE products.id = spesifikasi_product_details.product_id))'
                        );
                    })
            )
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            // ->afterStateUpdated(function ($state, callable $set) {
            //     if (!$state)
            //         return;

            //     $spk = SPKMarketing::with('spesifikasiProduct.details.product')->find($state);

            //     if (!$spk)
            //         return;

            //     $alphabet = range('A', 'Z'); // untuk batch_code
            //     $products = [];

            //     foreach ($spk->spesifikasiProduct?->details ?? [] as $detail) {
            //         $namaAlat = $detail->product?->name ?? '';
            //         $qty = $detail->quantity ?? 0;

            //         for ($i = 0; $i < $qty; $i++) {
            //             $batch = $alphabet[$i] ?? '';
            //             $prefix = strtoupper(Str::random(4));
            //             $mid = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            //             $monthCode = chr(64 + now()->month);
            //             $year = now()->format('y');
            //             $noSeri = $prefix . $mid . $batch . $monthCode . $year;

            //             $products[] = [
            //                 'nama_alat' => $namaAlat,
            //                 'tipe' => '',
            //                 'batch_code' => $batch,
            //                 'no_seri' => $noSeri,
            //                 'custom_standar' => '',
            //                 'jumlah' => 1,
            //             ];
            //         }
            //     }

            //     $set('identifikasiProduks', $products);
            // });
            // ->afterStateUpdated(function ($state, callable $set) {
            //     if (!$state) return;

            //     $spk = SPKMarketing::with([
            //         'spesifikasiProduct.details.product',
            //         'jadwalProduksi.identifikasiProduks',
            //     ])->find($state);

            //     if (!$spk) return;

            //     $alphabet = range('A', 'Z');
            //     $products = [];

            //     // Loop setiap detail produk di spesifikasi
            //     foreach ($spk->spesifikasiProduct?->details ?? [] as $detail) {
            //         $namaAlat = $detail->product?->name ?? '';
            //         $qty = $detail->quantity ?? 0;

            //         // Hitung berapa yang sudah ada di jadwal produksi
            //         $sudahAda = collect($spk->jadwalProduksi?->identifikasiProduks ?? [])
            //             ->where('nama_alat', $namaAlat)
            //             ->count();

            //         // Kalau masih ada sisa yang belum punya nomor seri
            //         if ($sudahAda < $qty) {
            //             $batch = $alphabet[$sudahAda] ?? '';
            //             $prefix = strtoupper(Str::random(4));
            //             $mid = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            //             $monthCode = chr(64 + now()->month);
            //             $year = now()->format('y');
            //             $noSeri = $prefix . $mid . $batch . $monthCode . $year;

            //             $products[] = [
            //                 'nama_alat' => $namaAlat,
            //                 'tipe' => '',
            //                 'batch_code' => $batch,
            //                 'no_seri' => $noSeri,
            //                 'custom_standar' => '',
            //                 'jumlah' => 1,
            //             ];

            //             break;
            //         }
            //     }

            //     $set('identifikasiProduks', $products);
            // });
            // ->afterStateUpdated(function ($state, callable $set) {
            //     if (!$state) return;

            //     $spk = SPKMarketing::with([
            //         'spesifikasiProduct.details.product',
            //         'jadwalProduksi.identifikasiProduks',
            //     ])->find($state);

            //     if (!$spk) return;

            //     $alphabet = range('A', 'Z');
            //     $products = [];

            //     foreach ($spk->spesifikasiProduct?->details ?? [] as $detail) {
            //         $namaAlat = $detail->product?->name ?? '';
            //         $qty = $detail->quantity ?? 0;

            //         // Hitung berapa unit dari produk ini yang udah dibuat
            //         $sudahAda = collect($spk->jadwalProduksi?->identifikasiProduks ?? [])
            //             ->where('nama_alat', $namaAlat)
            //             ->count();

            //         // Cek kalau masih ada sisa
            //         if ($sudahAda < $qty) {
            //             $batch = $alphabet[$sudahAda] ?? '';
            //             $prefix = strtoupper(Str::random(4));
            //             $mid = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            //             $monthCode = chr(64 + now()->month);
            //             $year = now()->format('y');
            //             $noSeri = $prefix . $mid . $batch . $monthCode . $year;

            //             $products[] = [
            //                 'nama_alat' => $namaAlat,
            //                 'tipe' => '',
            //                 'batch_code' => $batch,
            //                 'no_seri' => $noSeri,
            //                 'custom_standar' => '',
            //                 'jumlah' => 1,
            //             ];

            //             break;
            //         }
            //     }

            //     $set('identifikasiProduks', $products);
            // });
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spk = SPKMarketing::with([
                    'spesifikasiProduct.details.product',
                    'jadwalProduksi.identifikasiProduks',
                ])->find($state);

                if (!$spk) return;

                $alphabet = range('A', 'Z');
                $products = [];

                foreach ($spk->spesifikasiProduct?->details ?? [] as $detail) {
                    $product = $detail->product;
                    $namaAlat = $product?->name ?? '';
                    $qty = $detail->quantity ?? 0;

                    // Hitung sudah berapa kali alat ini dibuat
                    $sudahAda = collect($spk->jadwalProduksi?->identifikasiProduks ?? [])
                        ->where('nama_alat', $namaAlat)
                        ->count();

                    if ($sudahAda < $qty) {
                        // Cek kalau produk ini Mecmesin (id = 6)
                        $isMecmesin = $product?->id == 6 || Str::contains(strtolower($namaAlat), 'mecmesin');

                        if ($isMecmesin) {
                            // Tidak generate nomor seri untuk Mecmesin → isi "-"
                            $products[] = [
                                'nama_alat' => $namaAlat,
                                'tipe' => '',
                                'batch_code' => '-',
                                'no_seri' => '-',
                                'custom_standar' => '',
                                'jumlah' => 1,
                            ];
                        } else {
                            // Generate nomor seri normal
                            $batch = $alphabet[$sudahAda] ?? '';
                            $prefix = strtoupper(Str::random(4));
                            $mid = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                            $monthCode = chr(64 + now()->month);
                            $year = now()->format('y');
                            $noSeri = $prefix . $mid . $batch . $monthCode . $year;

                            $products[] = [
                                'nama_alat' => $namaAlat,
                                'tipe' => '',
                                'batch_code' => $batch,
                                'no_seri' => $noSeri,
                                'custom_standar' => '',
                                'jumlah' => 1,
                            ];
                        }

                        // Langsung keluar setelah satu alat ditambahkan
                        break;
                    }
                }

                $set('identifikasiProduks', $products);
            });
    }
}


            // ->afterStateUpdated(function ($state, callable $set) {
            //     if (!$state)
            //         return;

            //     $spk = SPKMarketing::with('spesifikasiProduct.details.product')->find($state);

            //     if (!$spk)
            //         return;

            //     $products = $spk->spesifikasiProduct?->details?->map(function ($detail) {
            //         return [
            //             'nama_alat' => $detail->product?->name ?? '',
            //             'jumlah' => $detail->quantity ?? 0,
            //         ];
            //     })->toArray();

            //     $set('identifikasiProduks', $products);
            // });
