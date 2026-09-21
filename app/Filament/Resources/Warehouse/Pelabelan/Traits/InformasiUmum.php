<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\Traits;

use App\Models\Quality\Release\ProductRelease;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiUmumSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                self::getSelect()
                    ->hiddenOn('edit'),

                self::dateInput('tanggal', 'Tanggal'),

                self::textInput('penanggung_jawab', 'Penanggung Jawab')

            ])
            ->columns([
                'default' => 1,
                'md' => $isEdit ? 2 : 3,
                'lg' => $isEdit ? 2 : 3,
            ]);
    }

    private static function getSelect()
    {
        return
            Select::make('release_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(
                ProductRelease::with('pengecekanPerforma.spkQC.spkMarketing')
                    ->where('status', 'Diketahui')
                    ->whereDoesntHave('qcPassed')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($item) => [
                        $item->id => $item->pengecekanPerforma?->spkQC?->spkMarketing?->no_spk
                    ])
            )
            ->getSearchResultsUsing(function (string $search) {

                return
                    ProductRelease::with('pengecekanPerforma.spkQC.spkMarketing')
                    ->where('status', 'Diketahui')
                    ->whereDoesntHave('qcPassed')
                    ->where(function ($q) use ($search) {
                        $q->whereHas(
                            'pengecekanPerforma.spkQC.spkMarketing',
                            fn($spkQ) => $spkQ->where('no_spk', 'like', "%{$search}%")
                        );
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($item) => [
                        $item->id => $item->pengecekanPerforma?->spkQC?->spkMarketing?->no_spk
                    ]);
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $release = ProductRelease::with('pengecekanPerforma.spkQC.spkMarketing')->find($state);

                $spec = $release?->pengecekanPerforma?->spkQC?->spkMarketing?->spesifikasiProduct;

                $product_name = $spec?->details?->first()?->product?->name ?? '-';

                $product_jumlah = $spec?->details?->first()?->quantity ?? '-';


                $set('details', [
                    [
                        'nama_produk'   => $product_name ?? '-',
                        'tipe'          => '',
                        'serial_number' => '',
                        'jumlah'        => $product_jumlah ?? '-',
                    ]
                ]);
            })
        ;
    }
}
