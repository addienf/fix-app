<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\Traits;

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
            ->collapsible()
            ->schema([

                Grid::make([
                    'default' => 1,
                    'md' => $isEdit ? 2 : 3,
                    'lg' => $isEdit ? 2 : 3,
                ])
                    ->schema([
                        //
                        self::getSelect()
                            ->hiddenOn('edit')
                            ->placeholder('Pilih No SPK'),

                        self::textInput('tipe', 'Type/Model'),

                        self::textInput('volume', 'Volume'),

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
            ->native(false)
            ->preload()
            ->required()
            ->options(function () {
                return
                    SPKQuality::whereDoesntHave('pengecekanElectrical')
                    ->where('status_penerimaan', 'Diterima')
                    ->latest()
                    ->limit(10)
                    ->pluck('no_spk', 'id');
            })
            ->getSearchResultsUsing(function ($search) {
                return
                    SPKQuality::whereDoesntHave('pengecekanElectrical')
                    ->where('status_penerimaan', 'Diterima')
                    ->whereHas('spkMarketing', function ($q) use ($search) {
                        $q->where('no_spk', 'like', "%{$search}%");
                    })
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
