<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

trait TabelChecklist
{
    use SimpleFormResource, HasAutoNumber;
    public static function getTabelChecklistSection()
    {
        $defaultParts = collect(config('walkinChamber'))
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
                    ->default($defaultParts)
                    ->label('')
                    ->schema([

                        Grid::make(7)
                            ->schema([
                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->hidden(fn(callable $get) => blank($get('mainPart')))
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ])
                                    ->columnSpan(3),

                                TextInput::make('before')
                                    ->label('Before')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('after')
                                    ->label('After')
                                    ->required()
                                    ->columnSpan(1),

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
                                    ->required()
                                    ->columnSpan(1),
                            ]),

                        Repeater::make('parts')
                            ->label('')
                            ->schema([

                                Textarea::make('part')
                                    ->rows(3)
                                    ->columnSpan(3)
                                    ->required(),

                                TextInput::make('before')
                                    ->columnSpan(1)
                                    ->required(),

                                TextInput::make('after')
                                    ->columnSpan(1)
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
                                    ->columnSpan(1)
                                    ->required(),

                            ])
                            ->addActionLabel('Tambah Checklist')
                            ->columns(7)
                            // ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),

                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
            ]);
    }
}
