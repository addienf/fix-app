<?php

namespace App\Filament\Resources\Quality\Pengecekan\Traits;

use App\Models\Production\SPK\SPKQuality;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

trait ChamberIdentification
{
    use SimpleFormResource;
    protected static function getChamberIdentificationSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Chamber Identification')
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => $isEdit ? 3 : 2,
                    'lg' => $isEdit ? 3 : 2,
                ])
                    ->schema([

                        self::getSelect()
                            ->hiddenOn('edit')
                            ->placeholder('Pilin No SPK'),

                        self::textInput('tipe', 'Type/Model'),

                        self::textInput('volume', 'Volume'),

                        self::textInput('serial_number', 'S/N'),

                    ]),
            ]);
    }

    private static function getSelect()
    {
        return
            Select::make('spk_qualities_id')
            ->label('Nomor SPK QC / No Seri')
            ->placeholder('Pilih Nomor SPK QC / No Seri')
            ->searchable()
            ->preload()
            ->reactive()
            ->required()
            ->options(function () {
                return
                    SPKQuality::whereDoesntHave('pengecekanPerforma')
                    ->where('status_penerimaan', 'Diterima')
                    ->latest()
                    ->limit(10)
                    ->pluck('no_spk', 'id');
            })
            ->getSearchResultsUsing(function ($search) {
                return
                    SPKQuality::whereDoesntHave('pengecekanPerforma')
                    ->where('status_penerimaan', 'Diterima')
                    ->whereHas(
                        'spkQC.spkMarketing.no_spk',
                        fn($q) => $q->where('no_spk', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->pluck('no_spk', 'id');
            });
    }

    public static function getNote()
    {
        return Card::make('')
            ->schema([

                Textarea::make('note')
                    ->required()
                    ->label('Note')
                    ->columnSpanFull()

            ]);
    }
}
