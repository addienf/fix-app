<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

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
                        collect(config('summaryNonSS.fields'))->map(function ($label, $key) {
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
