<?php

namespace App\Filament\Resources\Production\Jadwal\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

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

                        self::selectInputSPK('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                            ->hiddenOn('edit')
                            ->placeholder('Pilih Nomor SPK')
                    ])

            ]);
    }

    protected static function standardSection(): Section
    {
        return Section::make('Standard')
            ->schema([
                // FileUpload::make('file_upload')
                //     ->label('File Pendukung')
                //     ->directory('Production/Jadwal/Files')
                //     ->acceptedFileTypes(['application/pdf'])
                //     ->maxSize(10240)
                //     ->required()
                //     ->columnSpanFull()
                //     ->helperText('Drawing wajib dilampirkan'),
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

    protected static function selectInputSPK(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
            ->relationship(
                $relation,
                $title,
                fn($query) => $query->where('status_penerimaan', 'Diterima')->whereDoesntHave('jadwalProduksi')
            )
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $spk = SPKMarketing::with('spesifikasiProduct.details.product')->find($state);

                if (!$spk)
                    return;

                $products = $spk->spesifikasiProduct?->details?->map(function ($detail) {
                    return [
                        'nama_produk' => $detail->product?->name ?? '',
                        'jumlah' => $detail->quantity ?? 0,
                    ];
                })->toArray();

                $set('details', $products);
            });
    }
}
