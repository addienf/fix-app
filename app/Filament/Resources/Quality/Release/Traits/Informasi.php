<?php

namespace App\Filament\Resources\Quality\Release\Traits;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait Informasi
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiSection()
    {
        return
            Section::make('Informasi Umum')
            ->schema([
                self::getSelect()
                    ->hiddenOn('edit')
                    ->placeholder('Pilih Nomor SPK'),

                static::newAutoNmberField('no_order_release', 'Release Order No', [
                    'prefix' => 'QKS',
                    'section' => 'QA',
                    'type' => 'PR',
                    'table' => 'product_releases',
                ])
                    ->hiddenOn('edit'),

                self::textInput('product', 'The Product'),

                self::textInput('batch', 'Batch No'),

                self::textareaInput('remarks', 'Remark')->columnSpanFull(),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
            ])
            ->collapsible();
    }

    private static function getSelect()
    {
        return
            Select::make('pengecekan_performa_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(function () {
                return PengecekanPerforma::with([
                    'spkQC.spkMarketing.jadwalProduksi.identifikasiProduks'
                ])
                    ->whereDoesntHave('productRelease')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $jadwal = $item->spkQC?->spkMarketing?->jadwalProduksi;
                        $noSpk = $item->spkQC?->spkMarketing?->no_spk ?? '-';
                        $noSeri = $jadwal?->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-';

                        return [
                            $item->id => "{$noSpk} - {$noSeri}"
                        ];
                    });
            })
            ->getSearchResultsUsing(function (string $search) {
                return PengecekanPerforma::with([
                    'spkQC.spkMarketing.jadwalProduksi.identifikasiProduks'
                ])
                    ->where(function ($query) use ($search) {
                        $query->whereHas('spkQC.spkMarketing', function ($q) use ($search) {
                            $q->where('no_spk', 'like', "%{$search}%")
                                ->orWhereHas('jadwalProduksi.identifikasiProduks', function ($ipq) use ($search) {
                                    $ipq->where('no_seri', 'like', "%{$search}%");
                                });
                        });
                    })
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $jadwal = $item->spkQC?->spkMarketing?->jadwalProduksi;
                        $noSpk = $item->spkQC?->spkMarketing?->no_spk ?? '-';
                        $noSeri = $jadwal?->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-';

                        return [
                            $item->id => "{$noSpk} - {$noSeri}"
                        ];
                    });
            })
            ->getOptionLabelUsing(function ($value) {
                $item = PengecekanPerforma::with([
                    'spkQC.spkMarketing.jadwalProduksi.identifikasiProduks'
                ])->find($value);

                if (! $item) {
                    return null;
                }

                $jadwal = $item->spkQC?->spkMarketing?->jadwalProduksi;
                $noSpk = $item->spkQC?->spkMarketing?->no_spk ?? '-';
                $noSeri = $jadwal?->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-';

                return "{$noSpk} - {$noSeri}";
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pengecekan = PengecekanPerforma::with('spkQC.spkMarketing')->find($state);

                $product_name = $pengecekan?->spkQC?->spkMarketing?->spesifikasiProduct?->details?->first()?->product?->name ?? '-';

                $set('product', $product_name);
            });
    }
}
