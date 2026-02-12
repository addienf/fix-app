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

        $months = collect(range(1, 12))
            ->mapWithKeys(fn($m) => [
                $m => Carbon::create(2025, $m, 1)->translatedFormat('F')
            ])
            ->toArray();

        $years = collect(range(now()->year - 5, now()->year + 1))
            ->mapWithKeys(fn($y) => [$y => $y])
            ->toArray();

        return $form
            ->schema([
                Section::make()
                    ->schema([

                        Grid::make(6)
                            ->schema([
                                Select::make('selectedDepartment')
                                    ->label('Pilih Departemen')
                                    ->options(collect(config('models'))
                                        ->keys()
                                        ->mapWithKeys(fn($key) => [$key => ucfirst($key)]))
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, callable $set) => $set('selectedModel', null))
                                    ->columnSpan(2),

                                Select::make('selectedModel')
                                    ->label('Pilih Model')
                                    ->options(function (callable $get) {
                                        $department = $get('selectedDepartment');
                                        if (!$department) return [];

                                        return collect(config("models.$department"))
                                            ->mapWithKeys(fn($item, $key) => [$key => $item['label']])
                                            ->toArray();
                                    })
                                    ->placeholder('Pilih Model')
                                    ->visible(fn(callable $get) => filled($get('selectedDepartment')))
                                    ->reactive()
                                    ->columnSpan(2),

                                Select::make('selectedMonth')
                                    ->label('Filter Bulan')
                                    ->options($months)
                                    ->nullable()
                                    ->default(null)
                                    ->placeholder('Semua Bulan')
                                    ->reactive()
                                    ->columnSpan(2),

                                // 🔥 YEAR (AMAN)
                                Select::make('selectedYear')
                                    ->label('Filter Tahun')
                                    ->options($years)
                                    ->nullable()
                                    ->default(null)
                                    ->placeholder('Semua Tahun')
                                    ->reactive()
                                    ->columnSpan(2),
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
