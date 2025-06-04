<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Carbon;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => Carbon::create()->month($month)->format('F')];
        })->toArray();

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('selectedModel')
                            ->label('Select Model')
                            ->placeholder('Pilih Model')
                            ->options(
                                collect(config('models'))
                                    ->mapWithKeys(fn($item, $key) => [$key => $item['label']])
                                    ->toArray()
                            )
                            ->default('user')
                            ->reactive(),
                        Select::make('selectedMonth')
                            ->label('Filter by Month')
                            ->placeholder('Pilih Bulan')
                            ->options($months)
                            ->default(Carbon::now()->month)
                            ->reactive(),
                    ])
                    ->columns(2),
            ]);
    }

    public function getFilters(): array
    {
        return [
            'selectedModel' => 'product',
            'selectedMonth' => Carbon::now()->month,
        ];
    }
}
