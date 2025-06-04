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
        $year = now()->year;
        $config = $this->getSelectedModelConfig('user');

        return "Total Data {$config['label']} Tahun - {$year}";
    }

    protected function getData(): array
    {

        $year = now()->year;
        $start = Carbon::create($year, 1, 1)->startOfDay();
        $end = Carbon::create($year, 12, 31)->endOfDay();

        $config = $this->getSelectedModelConfig('user');
        $modelClass = $config['class'];
        $label = $config['label'];

        $cacheKey = $this->getCacheKey('chart-yearly', $config['key'], $year);

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($modelClass, $start, $end) {
            return Trend::query($modelClass::query())
                ->between($start, $end)
                ->perMonth()
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => "Jumlah Data {$label} per Bulan",
                    'data' => collect(range(1, 12))->map(function ($month) use ($data) {
                        return $data->firstWhere(
                            fn(TrendValue $val) => Carbon::parse($val->date)->month === $month
                        )?->aggregate ?? 0;
                    }),
                ],
            ],
            'labels' => $this->getMonthLabels(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
