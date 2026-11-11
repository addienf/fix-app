<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Traits\HasModelFilter;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    use InteractsWithPageFilters;
    use HasModelFilter;

    protected static ?int $sort = 1;

    public function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        $dept  = $this->filters['selectedDepartment'] ?? null;
        $model = $this->filters['selectedModel'] ?? null;
        $rawMonth = $this->filters['selectedMonth'] ?? null;
        $rawYear  = $this->filters['selectedYear'] ?? null;

        $month = is_numeric($rawMonth) ? (int) $rawMonth : (int) now()->month;
        $year  = is_numeric($rawYear)  ? (int) $rawYear  : (int) now()->year;

        if (!$dept || !$model) {
            return [];
        }

        $config = $this->getSelectedModelConfig($dept, $model);
        if (!$config) {
            return [];
        }

        $class = $config['class'];
        $label = $config['label'];

        $monthlyKey = "stat-monthly-{$config['key']}-{$year}-{$month}";

        $monthlyCount = Cache::remember($monthlyKey, now()->addMinutes(10), function () use ($class, $month, $year) {
            return $class::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();
        });

        $yearlyKey = "stat-yearly-{$config['key']}-{$year}";

        $yearlyCount = Cache::remember($yearlyKey, now()->addMinutes(10), function () use ($class, $year) {
            return $class::whereYear('created_at', $year)->count();
        });

        Carbon::setLocale('id');
        $bulanNama = Carbon::create($year, $month, 1)->translatedFormat('F');

        return [
            Stat::make("Total {$label} {$bulanNama} {$year}", $monthlyCount)
                ->icon('heroicon-o-chart-bar')
                ->iconColor('success')
                ->description("Data bulan {$bulanNama} tahun {$year}"),

            Stat::make("Total {$label} Tahun {$year}", $yearlyCount)
                ->icon('heroicon-o-chart-bar')
                ->iconColor('info')
                ->description("Akumulasi selama tahun {$year}"),
        ];
    }
}
