<?php

namespace App\Filament\Resources\Production\SPK\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Carbon;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiUmumSection($form): Section
    {
        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                    'lg' => 2,
                ])
                    ->schema([
                        self::autoNumberField2('no_spk', 'Nomor SPK', [
                            'prefix' => 'QKS',
                            'section' => 'PRO',
                            'type' => 'SQ',
                            'table' => 'spk_qualities',
                        ])
                            ->hiddenOn('edit'),

                        self::selectMaterialID()
                            ->placeholder('Pilih Nomor SPK')
                            ->hiddenOn('edit'),

                        self::textInput('dari', 'Dari'),

                        self::textInput('kepada', 'Kepada'),
                    ]),
            ]);
    }

    private static function selectMaterialID(): Select
    {
        return
            Select::make('spk_marketing_id')
            ->label('No SPK / Nomor Seri')
            ->placeholder('Pilih No SPK / Nomor Seri')
            ->required()
            ->searchable()
            ->reactive()
            ->options(function () {
                return
                    SPKMarketing::with(['jadwalProduksi.identifikasiProduks'])
                    ->whereDoesntHave('spkQC')
                    ->where('status_penerimaan', 'Diterima')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $nomorSeri = $item->jadwalProduksi
                            ?->identifikasiProduks
                            ?->first()?->no_seri;

                        $label = $nomorSeri
                            ? "{$item->no_spk} - {$nomorSeri}"
                            : $item->no_spk;

                        return [$item->id => $label];
                    });
            })
            ->getSearchResultsUsing(function ($search) {
                return
                    SPKMarketing::with(['jadwalProduksi.identifikasiProduks'])
                    ->whereDoesntHave('spkQC')
                    ->where('status_penerimaan', 'Diterima')
                    ->where(function ($q) use ($search) {
                        $q->where('no_spk', 'like', "%{$search}%")
                            ->orWhereHas('jadwalProduksi.identifikasiProduks', function ($q2) use ($search) {
                                $q2->where('no_seri', 'like', "%{$search}%");
                            });
                    })
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $nomorSeri = $item->jadwalProduksi
                            ?->identifikasiProduks
                            ?->first()?->no_seri;

                        $label = $nomorSeri
                            ? "{$item->no_spk} - {$nomorSeri}"
                            : $item->no_spk;

                        return [$item->id => $label];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set, callable $get) {

                if (!$state) return;

                $spk = SPKMarketing::with('spesifikasiProduct')->find($state);

                $noUrs  = $spk?->spesifikasiProduct?->urs?->no_urs ?? '-';
                $jumlah = $spk?->spesifikasiProduct?->details?->sum('quantity') ?? '-';
                $namaProduk  = $spk?->spesifikasiProduct?->details?->first()?->product?->name ?? '-';
                $rencana = $spk?->spesifikasiProduct?->estimasi_pengiriman
                    ? Carbon::parse($spk->spesifikasiProduct->estimasi_pengiriman)->translatedFormat('d F Y')
                    : '-';

                $nomorSeri = $spk?->jadwalProduksi?->identifikasiProduks?->first()?->no_seri ?? '';

                $details = $get('details') ?? [];

                foreach ($details as $index => $item) {
                    $set("details.{$index}.nama_produk", $namaProduk);
                    $set("details.{$index}.nomor_seri", $nomorSeri);
                    $set("details.{$index}.jumlah", $jumlah);
                    $set("details.{$index}.no_urs", $noUrs);
                    $set("details.{$index}.rencana_pengiriman", $rencana);
                }
            });
    }
}
