<?php

namespace App\Filament\Resources\Quality\Standarisasi\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection($form)
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make($isEdit ? 1 : 2)
                    ->schema([
                        static::selectInputSPK()
                            ->hiddenOn('edit'),

                        static::dateInput('tanggal', 'Tanggal'),
                    ]),
            ]);
    }

    protected static function selectInputSPK(): Select
    {
        return
            Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            // ->relationship(
            //     'spk',
            //     'no_spk',
            //     fn($query) => $query
            //         ->whereHas('permintaan.serahTerimaBahan', function ($query) {
            //             $query->where('status_penerimaan', 'Diterima');
            //         })->whereDoesntHave('standarisasi')

            // )
            ->relationship(
                'spk',
                'no_spk',
                fn($query) => $query->whereIn('id', Cache::rememberForever(
                    SPKMarketing::$CACHE_KEYS['standarisasi'],
                    fn() => SPKMarketing::whereHas('permintaan.serahTerimaBahan', function ($query) {
                        $query->where('status_penerimaan', 'Diterima');
                    })
                        ->whereDoesntHave('standarisasi')
                        ->pluck('id')
                        ->toArray()
                ))
            )
            ->placeholder('Pilin No SPK')
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
    }
}
