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
        $selectedDepartment = $this->filters['selectedDepartment'] ?? null;
        $selectedModel = $this->filters['selectedModel'] ?? null;
        $selectedMonth = $this->filters['selectedMonth'] ?? now()->month;
        $monthName = Carbon::create()->month($selectedMonth)->translatedFormat('F');

        if (!$selectedModel) {
            return '';
        }

        // Cek jika department atau model tidak valid
        if (!$selectedDepartment || !config("models.$selectedDepartment.$selectedModel")) {
            return 'Pilih Model Terlebih Dahulu';
        }

        $config = $this->getSelectedModelConfig($selectedDepartment, $selectedModel);
        return $config ? "Total Data {$config['label']} Bulan - {$monthName}" : "Pilih Model Terlebih Dahulu";
    }

    protected function getData(): array
    {
        $selectedDepartment = $this->filters['selectedDepartment'] ?? null;
        $selectedModel = $this->filters['selectedModel'] ?? null;
        $month = $this->filters['selectedMonth'] ?? now()->month;
        $year = now()->year;

        if (!$selectedDepartment || !$selectedModel) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $config = $this->getSelectedModelConfig($selectedDepartment, $selectedModel);

        if (!$config) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

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

        // Sembunyikan widget jika belum memilih model yang valid
        return $selectedDepartment && $selectedModel && config("models.$selectedDepartment.$selectedModel");
    }

    protected function getSelectedModelConfig(string $department, string $modelKey): ?array
    {
        $config = config("models.$department.$modelKey");

        if (!$config || !isset($config['model'])) {
            return null;
        }

        return [
            'key' => $modelKey,
            'label' => $config['label'],
            'class' => $config['model'],
        ];
    }
}
