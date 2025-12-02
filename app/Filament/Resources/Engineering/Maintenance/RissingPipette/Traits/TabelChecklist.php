<?php

namespace App\Filament\Resources\Engineering\Maintenance\RissingPipette\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

trait TabelChecklist
{
    use SimpleFormResource, HasAutoNumber;
    public static function getTabelChecklistSection()
    {
        $parts = collect(config('rissingPipette.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        return Section::make('Tabel Checklist')
            ->collapsible()
            ->relationship('detail')
            ->schema([

                Repeater::make('checklist')
                    ->default($parts)
                    ->label('')
                    ->schema([

                        TextInput::make('part')
                            ->columnSpan(3)
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ])
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
                    ->columns(7)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false),

            ]);
    }
}
