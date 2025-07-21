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
        $selectedDepartment = $this->filters['selectedDepartment'] ?? null;
        $selectedModel = $this->filters['selectedModel'] ?? null;
        $selectedMonth = $this->filters['selectedMonth'] ?? now()->month;
        $year = now()->year;

        // Jangan tampilkan apa pun jika model belum dipilih
        if (!$selectedModel) {
            return [];
        }

        // Cek jika department atau model tidak valid
        if (!$selectedDepartment || !config("models.$selectedDepartment.$selectedModel")) {
            return [
                Stat::make('Data tidak tersedia', 0)
                    ->description('Model tidak ditemukan di departemen tersebut.'),
            ];
        }

        // Ambil data model dari config
        $model = $this->getSelectedModelConfig($selectedDepartment, $selectedModel);
        $modelKey = $model['key'];
        $label = $model['label'];
        $modelClass = $model['class'];

        // Cache total data bulan ini
        $monthlyKey = $this->getCacheKey('stat-monthly', $modelKey, "{$year}-{$selectedMonth}");
        $monthlyCount = Cache::remember($monthlyKey, now()->addMinutes(10), function () use ($modelClass, $selectedMonth, $year) {
            return $modelClass::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $year)
                ->count();
        });

        // Cache total data tahun ini
        $yearlyKey = $this->getCacheKey('stat-yearly', $modelKey, $year);
        $yearlyCount = Cache::remember($yearlyKey, now()->addMinutes(10), function () use ($modelClass, $year) {
            return $modelClass::whereYear('created_at', $year)->count();
        });

        // Format nama bulan
        Carbon::setLocale('id');
        $bulanNama = Carbon::create()->month($selectedMonth)->translatedFormat('F');

        return [
            Stat::make("Total Data {$label} Bulan Ini", $monthlyCount)
                ->icon('heroicon-o-chart-bar')
                ->iconColor('success')
                ->description("Model: {$label}, Bulan: {$bulanNama}")
                ->chart([0, 0, 0, 0, 0, 0]) // Dummy chart, bisa diganti real data
                ->chartColor('success'),

            Stat::make("Total Data {$label} Tahun Ini", $yearlyCount)
                ->icon('heroicon-o-chart-bar')
                ->iconColor('info')
                ->description("Tahun: {$year}")
                ->chart([0, 0, 0, 0, 0, 0]) // Dummy chart, bisa diganti real data
                ->chartColor('info'),
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
