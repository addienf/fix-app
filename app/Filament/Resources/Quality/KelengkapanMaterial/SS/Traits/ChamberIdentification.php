<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\Traits;

use App\Models\Production\SPK\SPKQuality;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

trait ChamberIdentification
{
    protected static function getChamberIdentificationSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Chamber Identification')
            ->collapsible()
            ->schema([
                //

                self::getSelectedSPK()
                    ->hiddenOn('edit'),

                self::textInput('tipe', 'Type/Model'),

                self::textInput('ref_document', 'Ref Document'),
            ])
            ->columns([
                'default' => 1,
                'md' => $isEdit ? 2 : 3,
                'lg' => $isEdit ? 2 : 3,
            ]);
    }

    private static function getSelectedSPK()
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
                    SPKQuality::whereDoesntHave('kelengkapanMaterial')
                    ->where('status_penerimaan', 'Diterima')
                    ->latest()
                    ->limit(10)
                    ->pluck('no_spk', 'id');
            })
            ->getSearchResultsUsing(function ($search) {
                return
                    SPKQuality::whereDoesntHave('kelengkapanMaterial')
                    ->where('status_penerimaan', 'Diterima')
                    ->where('no_spk', 'like', "%{$search}%")
                    ->latest()
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
