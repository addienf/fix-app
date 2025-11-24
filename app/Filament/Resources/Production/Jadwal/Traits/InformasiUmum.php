<?php

namespace App\Filament\Resources\Production\Jadwal\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\SimpleFormResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait InformasiUmum
{
    use SimpleFormResource;
    protected static function informasiUmumSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make($isEdit ? 3 : 2)
                    ->schema([

                        self::dateInput('tanggal', 'Tanggal')
                            ->required(),

                        self::textInput('pic_name', 'Penanggung Jawab'),

                        self::textInput('no_surat', 'No Surat'),

                        self::select('spk_marketing_id', 'No SPK')
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

    private static function select(string $fieldName, string $label): Select
    {
        return
            Select::make($fieldName)
            ->label($label)
            ->placeholder("Pilih {$label}")
            ->native(false)
            ->searchable()
            ->reactive()
            ->required()
            ->options(function () {
                return SPKMarketing::query()
                    ->select(['id', 'no_spk'])
                    ->where('status_penerimaan', 'Diterima')
                    ->whereHas('spesifikasiProduct.details', function ($q) {
                        $q->whereRaw(
                            'quantity > (
                                SELECT COUNT(*)
                                FROM identifikasi_produks ip
                                JOIN jadwal_produksis jp ON jp.id = ip.jadwal_produksi_id
                                WHERE jp.spk_marketing_id = spk_marketings.id
                                AND ip.nama_alat = (
                                    SELECT name FROM products
                                    WHERE products.id = spesifikasi_product_details.product_id
                                )
                            )'
                        );
                    })

                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get()
                    ->pluck('no_spk', 'id');
            })
            ->getSearchResultsUsing(function (string $search) {

                // if ($search === '') {
                //     return Cache::get(SPKMarketing::$CACHE_PREFIXES['search_spk'], []);
                // }

                return SPKMarketing::query()
                    ->select(['id', 'no_spk'])
                    ->where('status_penerimaan', 'Diterima')
                    ->whereHas('spesifikasiProduct.details', function ($q) {
                        $q->whereRaw(
                            'quantity > (
                            SELECT COUNT(*)
                            FROM identifikasi_produks ip
                            JOIN jadwal_produksis jp ON jp.id = ip.jadwal_produksi_id
                            WHERE jp.spk_marketing_id = spk_marketings.id
                            AND ip.nama_alat = (
                                SELECT name FROM products
                                WHERE products.id = spesifikasi_product_details.product_id
                            )
                        )'
                        );
                    })
                    ->where('no_spk', 'LIKE', "%{$search}%")
                    ->limit(20)
                    ->pluck('no_spk', 'id');
            })
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

                    $sudahAda = collect($spk->jadwalProduksi?->identifikasiProduks ?? [])
                        ->where('nama_alat', $namaAlat)
                        ->count();

                    if ($sudahAda < $qty) {

                        $isMecmesin =
                            $product?->id == 6 ||
                            Str::contains(strtolower($namaAlat), 'mecmesin');

                        if ($isMecmesin) {
                            $products[] = [
                                'nama_alat' => $namaAlat,
                                'tipe' => '',
                                'batch_code' => '-',
                                'no_seri' => '-',
                                'custom_standar' => '',
                                'jumlah' => 1,
                            ];
                        } else {
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
                        break;
                    }
                }
                $set('identifikasiProduks', $products);
            });
    }
}
