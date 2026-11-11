<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasModelFilter;

class AdminChartYearWidget extends AdvancedChartWidget
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
        $year  = $this->filters['selectedYear'] ?? now()->year;

        if (!$dept || !$model) return '';

        $config = $this->getSelectedModelConfig($dept, $model);
        if (!$config) return '';

        return "Total Data {$config['label']} Tahun - {$year}";
    }

    protected function getData(): array
    {
        $dept  = $this->filters['selectedDepartment'] ?? null;
        $model = $this->filters['selectedModel'] ?? null;
        $year  = $this->filters['selectedYear'] ?? now()->year;

        if (!$dept || !$model) return ['datasets' => [], 'labels' => []];

        $config = $this->getSelectedModelConfig($dept, $model);
        if (!$config) return ['datasets' => [], 'labels' => []];

        $start = Carbon::create($year, 1, 1)->startOfYear();
        $end   = Carbon::create($year, 12, 31)->endOfYear();

        $cacheKey = "chart-yearly-{$config['key']}-{$year}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($config, $start, $end) {
            return Trend::query($config['class']::query())
                ->between($start, $end)
                ->perMonth()
                ->count();
        });

        return [
            'datasets' => [[
                'label' => "Jumlah per Bulan",
                'data' => collect(range(1, 12))->map(function ($m) use ($data) {
                    return $data->firstWhere(
                        fn(TrendValue $v) => Carbon::parse($v->date)->month === $m
                    )?->aggregate ?? 0;
                }),
            ]],
            'labels' => collect(range(1, 12))
                ->map(fn($m) => Carbon::create($year, $m, 1)->translatedFormat('M')),
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
}
