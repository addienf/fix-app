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

    // protected function getStats(): array
    // {
    //     $selectedDepartment = $this->filters['selectedDepartment'] ?? null;
    //     $selectedModel = $this->filters['selectedModel'] ?? null;
    //     $selectedMonth = $this->filters['selectedMonth'] ?? now()->month;
    //     $year = now()->year;

    //     // Jangan tampilkan apa pun jika model belum dipilih
    //     if (!$selectedModel) {
    //         return [];
    //     }

    //     // Cek jika department atau model tidak valid
    //     if (!$selectedDepartment || !config("models.$selectedDepartment.$selectedModel")) {
    //         return [
    //             Stat::make('Data tidak tersedia', 0)
    //                 ->description('Model tidak ditemukan di departemen tersebut.'),
    //         ];
    //     }

    //     // Ambil data model dari config
    //     $model = $this->getSelectedModelConfig($selectedDepartment, $selectedModel);
    //     $modelKey = $model['key'];
    //     $label = $model['label'];
    //     $modelClass = $model['class'];

    //     // Cache total data bulan ini
    //     $monthlyKey = $this->getCacheKey('stat-monthly', $modelKey, "{$year}-{$selectedMonth}");
    //     $monthlyCount = Cache::remember($monthlyKey, now()->addMinutes(10), function () use ($modelClass, $selectedMonth, $year) {
    //         return $modelClass::whereMonth('created_at', $selectedMonth)
    //             ->whereYear('created_at', $year)
    //             ->count();
    //     });

    //     // Cache total data tahun ini
    //     $yearlyKey = $this->getCacheKey('stat-yearly', $modelKey, $year);
    //     $yearlyCount = Cache::remember($yearlyKey, now()->addMinutes(10), function () use ($modelClass, $year) {
    //         return $modelClass::whereYear('created_at', $year)->count();
    //     });

    //     // Format nama bulan
    //     Carbon::setLocale('id');
    //     $bulanNama = Carbon::create()->month($selectedMonth)->translatedFormat('F');

    //     return [
    //         Stat::make("Total Data {$label} Bulan Ini", $monthlyCount)
    //             ->icon('heroicon-o-chart-bar')
    //             ->iconColor('success')
    //             ->description("Model: {$label}, Bulan: {$bulanNama}")
    //             ->chart([0, 0, 0, 0, 0, 0]) // Dummy chart, bisa diganti real data
    //             ->chartColor('success'),

    //         Stat::make("Total Data {$label} Tahun Ini", $yearlyCount)
    //             ->icon('heroicon-o-chart-bar')
    //             ->iconColor('info')
    //             ->description("Tahun: {$year}")
    //             ->chart([0, 0, 0, 0, 0, 0]) // Dummy chart, bisa diganti real data
    //             ->chartColor('info'),
    //     ];
    // }
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

        // 🔥 CARBON SUDAH AMAN
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
