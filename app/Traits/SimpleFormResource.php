<?php

namespace App\Traits;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

trait SimpleFormResource
{
    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function textareaInput(string $fieldName, string $label): Textarea
    {
        return Textarea::make($fieldName)
            ->label($label)
            ->rows(3)
            ->required()
            ->columnSpanFull();
    }

    protected static function selectInput(string $fieldName, string $label, string $relation, string $title): Select
    {
        return Select::make($fieldName)
            ->relationship($relation, $title)
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required();
    }

    protected static function dateInput(string $fieldName, string $label): DatePicker
    {
        return DatePicker::make($fieldName)
            ->label($label)
            ->required();
    }

    protected static function textColumn(string $fieldName, string $label): TextColumn
    {
        return TextColumn::make($fieldName)
            ->label($label)
            ->searchable()
            ->sortable();
    }
}
