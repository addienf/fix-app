<?php

namespace App\Filament\Widgets;

use App\Traits\HasModelFilter;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminChartWidget extends AdvancedChartWidget
{

    use InteractsWithPageFilters;
    use HasModelFilter;
    protected static ?int $sort = 2;
    protected static ?string $heading = null;
    protected static string $color = 'info';
    protected static ?string $icon = 'heroicon-o-chart-bar';
    protected static ?string $iconColor = 'info';
    protected static ?string $iconBackgroundColor = 'info';
    protected static ?string $label = null;

    public function getHeading(): string
    {
        $dept  = $this->filters['selectedDepartment'] ?? null;
        $model = $this->filters['selectedModel'] ?? null;
        $month = $this->filters['selectedMonth'] ?? now()->month;
        $year  = $this->filters['selectedYear'] ?? now()->year;

        if (!$dept || !$model) return '';

        $config = $this->getSelectedModelConfig($dept, $model);
        if (!$config) return '';

        $monthName = Carbon::create($year, $month, 1)->translatedFormat('F Y');

        return "Total Data {$config['label']} Bulan - {$monthName}";
    }

    protected function getData(): array
    {
        $dept  = $this->filters['selectedDepartment'] ?? null;
        $model = $this->filters['selectedModel'] ?? null;
        $month = $this->filters['selectedMonth'] ?? now()->month;
        $year  = $this->filters['selectedYear'] ?? now()->year;

        if (!$dept || !$model) return ['datasets' => [], 'labels' => []];

        $config = $this->getSelectedModelConfig($dept, $model);
        if (!$config) return ['datasets' => [], 'labels' => []];

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = Carbon::create($year, $month, 1)->endOfMonth();

        $cacheKey = "chart-daily-{$config['key']}-{$year}-{$month}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($config, $start, $end) {
            return Trend::query($config['class']::query())
                ->between($start, $end)
                ->perDay()
                ->count();
        });

        return [
            'datasets' => [[
                'label' => "Jumlah per Hari",
                'data'  => $data->map(fn(TrendValue $v) => $v->aggregate),
            ]],
            'labels' => $data->map(
                fn(TrendValue $v) =>
                Carbon::parse($v->date)->format('d M')
            ),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): ?array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }

    public function isVisible(): bool
    {
        $selectedDepartment = $this->filters['selectedDepartment'] ?? null;
        $selectedModel = $this->filters['selectedModel'] ?? null;

        return $selectedDepartment
            && $selectedModel
            && config("models.$selectedDepartment.$selectedModel");
    }

    protected function getSelectedModelConfig(string $department, string $modelKey): ?array
    {
        $config = config("models.$department.$modelKey");

        if (!$config || !isset($config['model'])) {
            return null;
        }

        return [
            'key'   => $modelKey,
            'label' => $config['label'],
            'class' => $config['model'],
        ];
    }
}
