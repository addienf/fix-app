<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\Traits;

use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiProduk
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiProdukSection()
    {

        return
            Section::make('Informasi Produk')
            ->collapsible()
            ->schema([
                self::selectMaterialID()
                    ->hiddenOn('edit')
                    ->columnSpanFull(),

                self::textInput('nama_produk', 'Nama Produk'),

                self::textInput('tipe', 'Tipe/Model'),

                self::textInput('no_spk', 'No SPK MKT'),

                self::dateInput('tanggal_selesai', 'Tanggal Produksi Selesai'),

                self::textInput('jumlah', 'Jumlah Unit'),

                self::selectKondisi(),

                self::textareaInput('deskripsi_kondisi', 'Deskripsi Produk')
                    ->columnSpanFull(),

            ])->columns([
                'default' => 1,
                'md' => 3,
                'lg' => 3,
            ]);
    }

    private static function selectMaterialID(): Select
    {
        return
            Select::make('pengecekan_material_id')
            ->label('No SPK / Nomor Seri')
            ->placeholder('Pilih No SPK / Nomor Seri')
            ->required()
            ->searchable()
            ->reactive()
            ->options(function () {
                return
                    PengecekanMaterialSS::with('spkQC.spkMarketing.spesifikasiProduct')
                    ->whereDoesntHave('penyerahan')
                    ->where('status_penyelesaian', 'Disetujui')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $spkNo = $std->spkQC->spkMarketing->no_spk ?? '-';

                        return [
                            $std->id => "{$spkNo}",
                        ];
                    });
            })
            ->getSearchResultsUsing(function ($search) {
                return
                    PengecekanMaterialSS::with('spkQC.spkMarketing.spesifikasiProduct')
                    ->whereDoesntHave('penyerahan')
                    ->whereHas(
                        'spkQC.spkMarketing.spesifikasiProduct',
                        fn($q) => $q->where('no_spk', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $spkNo = $std->spkQC->spkMarketing->no_spk ?? '-';

                        return [
                            $std->id => "{$spkNo}",
                        ];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {

                if (!$state) return;

                $pengecekan = PengecekanMaterialSS::with('spkQC.spkMarketing.spesifikasiProduct')->find($state);

                $namaProduk = $pengecekan?->spkQC?->spkMarketing?->spesifikasiProduct?->details?->first()?->product->name ?? '-';
                $spkNo = $pengecekan?->spkQC?->spkMarketing?->no_spk ?? '-';
                $jumlah = $pengecekan?->spkQC?->spkMarketing?->spesifikasiProduct?->details?->first()?->quantity ?? '-';

                $set('nama_produk', $namaProduk);
                $set('jumlah', $jumlah);
                $set('no_spk', $spkNo);
            });
    }
}
