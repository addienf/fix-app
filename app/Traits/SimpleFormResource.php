<?php

namespace App\Traits;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

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

    protected static function uploadField(
        string $name = '',
        string $label = '',
        string $directory = '',
        ?string $helperText = null,
        array $types = ['application/pdf', 'image/jpeg', 'image/png'],
        int $maxSize = 5120,
        bool $required = true,
        bool $optimize = true,
    ): FileUpload {
        return FileUpload::make($name)
            ->label($label)
            ->directory($directory)
            ->acceptedFileTypes($types)
            ->maxSize($maxSize)
            ->columnSpanFull()
            ->helperText($helperText ?? 'Unggah file (PDF/JPG/PNG), maksimal ' . ($maxSize / 1024) . ' MB.')
            ->required($required)
            ->openable()
            // ->downloadable()
            // ->previewable()
            ->afterStateUpdated(function ($state) use ($directory, $optimize) {
                // Kompres otomatis kalau file berupa gambar
                if ($optimize && $state) {
                    $path = Storage::disk('public')->path($state);
                    if (file_exists($path) && @exif_imagetype($path)) {
                        ImageOptimizer::optimize($path);
                    }
                }
            });
    }
}
