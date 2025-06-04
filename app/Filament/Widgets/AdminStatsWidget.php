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
        $selectedMonth = $this->filters['selectedMonth'] ?? now()->month;
        $year = now()->year;

        $model = $this->getSelectedModelConfig();
        $modelKey = $model['key'];
        $label = $model['label'];
        $modelClass = $model['class'];

        // Stat bulanan
        $monthlyKey = $this->getCacheKey('stat-monthly', $modelKey, "{$year}-{$selectedMonth}");
        $monthlyCount = Cache::remember($monthlyKey, now()->addMinutes(10), function () use ($modelClass, $selectedMonth, $year) {
            return $modelClass::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $year)
                ->count();
        });

        // Stat tahunan
        $yearlyKey = $this->getCacheKey('stat-yearly', $modelKey, $year);
        $yearlyCount = Cache::remember($yearlyKey, now()->addMinutes(10), function () use ($modelClass, $year) {
            return $modelClass::whereYear('created_at', $year)->count();
        });

        return [
            Stat::make("Total {$label} Bulan Ini", $monthlyCount)
                ->icon('heroicon-o-chart-bar')
                ->iconColor('success')
                ->description("Model: {$label}, Bulan: " . Carbon::create()->month($selectedMonth)->format('F'))
                ->chart([0, 0, 0, 0, 0, 0])
                ->chartColor('success'),

            Stat::make("Total {$label} Tahun Ini", $yearlyCount)
                ->icon('heroicon-o-chart-bar')
                ->iconColor('info')
                ->description("Tahun: {$year}")
                ->chart([0, 0, 0, 0, 0, 0])
                ->chartColor('info'),
        ];
    }
}