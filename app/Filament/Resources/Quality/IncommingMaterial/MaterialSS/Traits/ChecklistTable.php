<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Cache;

trait ChecklistTable
{
    use SimpleFormResource, HasAutoNumber;
    protected static function checklistTableSection(): Section
    {
        return Section::make('Checklist Table')
            ->relationship('detail')
            ->schema([

                Repeater::make('checklists')
                    ->label('')
                    ->schema([

                        Textarea::make('procedures')
                            ->disabled()
                            ->rows(6)
                            ->label('Procedures')
                            ->formatStateUsing(fn($state) => $state ??
                                "- Wipe of the dust, dirt, oil, and water on the surface of material\n- Make a mark on the upper, middle and buttom side of the material surface\n- Make a mark on the upper, middle and buttom side of the material surface"),

                        Textarea::make('expected_result')
                            ->disabled()
                            ->rows(6)
                            ->label('Expected Result')
                            ->formatStateUsing(fn($state) => $state ?? "There was no color change within 3 minutes after the liquid dropped on the surface that indicating materials is genuine SS304"),

                        Textarea::make('actual_result_1')
                            ->rows(6)
                            ->label('Actual Result')
                            ->placeholder('Masukan Penjelasan Keadaan Sebenarnya...'),

                        Textarea::make('procedures_2')
                            ->disabled()
                            ->rows(1)
                            ->formatStateUsing(fn($state) => $state ?? 'Visual check'),

                        Textarea::make('expected_result_2')
                            ->disabled()
                            ->rows(1)
                            ->label('Expected Result')
                            ->formatStateUsing(fn($state) => $state ?? 'No defect and rust found'),

                        Textarea::make('actual_result_2')
                            ->rows(1)
                            ->label('Actual Result')
                            ->placeholder('Masukan Penjelasan Keadaan Sebenarnya...'),


                    ])
                    ->columns(3)
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false),

                Repeater::make('details_tambahan')
                    ->label('Checklist Tambahan')
                    ->schema([

                        Textarea::make('procedures')
                            // ->disabled()
                            ->rows(3)
                            ->label('Procedures'),

                        Textarea::make('expected_result')
                            // ->disabled()
                            ->rows(3)
                            ->label('Expected Result'),

                        Textarea::make('actual_result_1')
                            ->rows(3)
                            ->label('Actual Result')
                            ->placeholder('Masukan Penjelasan Keadaan Sebenarnya...'),

                    ])
                    ->default([])
                    ->addActionLabel('Tambah Checklist')
                    ->columns(3)

            ]);
    }
}
