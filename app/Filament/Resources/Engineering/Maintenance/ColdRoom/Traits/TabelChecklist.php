<?php

namespace App\Filament\Resources\Engineering\Maintenance\ColdRoom\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

trait TabelChecklist
{
    use SimpleFormResource, HasAutoNumber;
    public static function getTabelChecklistSection()
    {
        $defaultParts = collect(config('coldRoom'))
            ->map(function ($group) {
                return [
                    'mainPart' => $group['mainPart'],
                    'parts' => collect($group['parts'])
                        ->map(fn($part) => ['part' => $part])
                        ->toArray(),
                ];
            })
            ->toArray();

        return Section::make('Tabel Checklist')
            ->collapsible()
            ->relationship('detail')
            ->schema([

                Repeater::make('checklist')
                    ->extraAttributes([
                        'class' => 'bg-gray-100 dark:bg-gray-800 rounded-lg p-4'
                    ])
                    ->default($defaultParts)
                    ->label('')
                    ->schema([

                        Grid::make(5)
                            ->schema([
                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->hidden(fn(callable $get) => blank($get('mainPart')))
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ])
                                    ->columnSpan(3),

                                Select::make('accepted')
                                    ->label('Accepted')
                                    ->required()
                                    ->options([
                                        'yes' => 'Yes',
                                        'no' => 'No',
                                        'na' => 'NA',
                                    ])
                                    ->columnSpan(1),

                                TextInput::make('remark')
                                    ->label('Remark')
                                    // ->required()
                                    ->columnSpan(1),
                            ]),

                        Repeater::make('parts')
                            ->label('')
                            ->schema([

                                TextInput::make('part')
                                    ->columnSpan(3)
                                    ->required(),

                                Select::make('accepted')
                                    ->options([
                                        'yes' => 'Yes',
                                        'no' => 'No',
                                        'na' => 'NA',
                                    ])
                                    ->columnSpan(1)
                                    ->required(),

                                TextInput::make('remark')
                                    ->columnSpan(1),
                                // ->required(),

                            ])
                            ->columns(5)
                            ->addActionLabel('Tambah Part Checklist')
                            ->addable(true)
                            ->deletable(true)
                            ->reorderable(false),

                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)

            ]);
    }
}
