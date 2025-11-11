<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

trait Summary
{
    use SimpleFormResource, HasAutoNumber;
    protected static function summarySection(): Section
    {
        return Section::make('Summary & Quantity')
            ->label('')
            ->relationship('summary')
            ->schema([
                Grid::make(2)
                    ->schema(
                        collect(config('summarySS.fields'))->map(function ($label, $key) {
                            return [
                                // Kolom kiri: label
                                Placeholder::make("summary_label_{$key}")
                                    ->content(new HtmlString('<div class="px-1 py-4 rounded-md text-md">' . e($label) . '</div>'))
                                    ->disableLabel(),

                                // Kolom kanan: input
                                TextInput::make("summary.{$key}")
                                    ->numeric()
                                    ->label('')
                                    ->placeholder('0')
                                    ->extraAttributes([
                                        'class' => 'px-3 py-1 border border-gray-300 text-sm w-full',
                                    ]),
                            ];
                        })->flatten(1)->toArray()
                    ),
            ]);
    }
}
