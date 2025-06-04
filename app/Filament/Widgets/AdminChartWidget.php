<?php

namespace App\Filament\Widgets;

use App\Models\User;
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
        $selectedMonth = $this->filters['selectedMonth'] ?? now()->month;
        $monthName = Carbon::create()->month($selectedMonth)->format('F');

        $config = $this->getSelectedModelConfig();
        return "Total Data {$config['label']} Bulan - {$monthName}";
    }

    protected function getData(): array
    {
        $month = $this->filters['selectedMonth'] ?? now()->month;
        $year = now()->year;

        $config = $this->getSelectedModelConfig();
        $modelClass = $config['class'];
        $label = $config['label'];
        $start = Carbon::create($year, $month)->startOfMonth();
        $end = Carbon::create($year, $month)->endOfMonth();

        $cacheKey = $this->getCacheKey('chart-daily', $config['key'], "{$year}-{$month}");

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($modelClass, $start, $end) {
            return Trend::query($modelClass::query())
                ->between($start, $end)
                ->perDay()
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => "Jumlah Data {$label} per Hari",
                    'data' => $data->map(fn(TrendValue $val) => $val->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $val) => Carbon::parse($val->date)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // protected function getOptions(): ?array
    // {
    //     return [
    //         'scales' => [
    //             'y' => [
    //                 'ticks' => [
    //                     'precision' => 0,
    //                 ],
    //             ],
    //         ],
    //     ];
    // }
}