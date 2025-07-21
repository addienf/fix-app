<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminChartWidget;
use App\Filament\Widgets\AdminChartYearWidget;
use App\Filament\Widgets\AdminStatsWidget;
use Filament\Forms\Components\Grid;
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
        Carbon::setLocale('id'); // Set locale ke Bahasa Indonesia

        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => Carbon::create()->month($month)->translatedFormat('F')];
        })->toArray();

        return $form
            ->schema([
                Section::make()
                    ->schema([

                        Grid::make(6)
                            ->schema([
                                Select::make('selectedDepartment')
                                    ->label('Pilih Departemen')
                                    ->options(collect(config('models'))->keys()->mapWithKeys(fn($key) => [$key => ucfirst($key)]))
                                    ->reactive()
                                    // ->default('general')
                                    ->afterStateUpdated(fn($state, callable $set) => $set('selectedModel', null))
                                    ->columnSpan(fn(callable $get) => filled($get('selectedDepartment')) ? 2 : 3),

                                Select::make('selectedModel')
                                    ->label('Pilih Model')
                                    ->options(function (callable $get) {
                                        $department = $get('selectedDepartment');
                                        if (!$department) return [];

                                        return collect(config("models.$department"))
                                            ->mapWithKeys(fn($item, $key) => [$key => $item['label']])
                                            ->toArray();
                                    })
                                    // ->default('user')
                                    ->placeholder('Pilih Model')
                                    ->visible(fn(callable $get) => filled($get('selectedDepartment')))
                                    ->reactive()
                                    ->columnSpan(fn(callable $get) => filled($get('selectedDepartment')) ? 2 : 0),

                                Select::make('selectedMonth')
                                    ->label('Filter by Month')
                                    ->placeholder('Pilih Bulan')
                                    ->options($months)
                                    ->default(Carbon::now()->month)
                                    ->reactive()
                                    ->columnSpan(fn(callable $get) => filled($get('selectedDepartment')) ? 2 : 3),
                            ])
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
