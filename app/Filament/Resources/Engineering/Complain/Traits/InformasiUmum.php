<?php

namespace App\Filament\Resources\Engineering\Complain\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

trait InformasiUmum
{
    use SimpleFormResource;
    public static function getInformasiUmumSection($form)
    {
        $lastValue2 = Complain::latest('form_no')->value('form_no');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make($isEdit ? 3 : 2)
                    ->schema([
                        TextInput::make('form_no')
                            ->label('Nomor Form')
                            ->default('SR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)))
                            ->placeholder($lastValue2 ? "Data Terakhir : {$lastValue2}" : 'Data Belum Tersedia')
                            ->hiddenOn('edit')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::dateInput('tanggal', 'Tanggal'),

                        self::textInput('dari', 'Dari'),

                        self::textInput('kepada', 'Kepada')
                            ->placeholder('Engineering')
                    ]),
            ]);
    }
}
