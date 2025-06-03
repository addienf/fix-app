<?php

namespace App\Filament\Widgets;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

class AdminStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected function getStats(): array
    {
        $totalSpecM = SpesifikasiProduct::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $totalSPKM = SPKMarketing::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $totalSalesThisMonth = $totalSpecM + $totalSPKM;

        $monthlySales = collect(range(1, 12))->map(function ($month) {
            return SPKMarketing::whereMonth('created_at', $month)
                ->whereYear('created_at', now()->year)
                ->count();
        });

        $totalSpecY = SpesifikasiProduct::query()
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->count();
        $totalSPKY = SPKMarketing::query()
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->count();

        $totalSalesThisYear = $totalSpecY + $totalSPKY;

        $yearlySales = collect(range(now()->year - 4, now()->year))->map(function ($year) {
            return SPKMarketing::whereYear('created_at', $year)->count();
        });


        $totalSales = SpesifikasiProduct::count() + SPKMarketing::count();
        return [
            //
            Stat::make('Total Data Dari Department Sales Bulan Ini', number_format($totalSalesThisMonth))
                ->icon('heroicon-o-chart-bar')
                ->description('Gabungan data dari Spesifikasi dan SPK')
                ->descriptionIcon('heroicon-o-arrow-trending-up', 'before')
                ->descriptionColor('success')
                ->iconColor('primary')
                ->progress($totalSalesThisMonth / $totalSales * 100)
                ->progressBarColor('success')
                ->chart($monthlySales->toArray()),
            Stat::make('Total Data Dari Department Sales Tahun Ini', number_format($totalSalesThisYear))
                ->icon('heroicon-o-chart-bar')
                ->description('Gabungan data dari Spesifikasi dan SPK')
                ->descriptionIcon('heroicon-o-arrow-trending-up', 'before')
                ->descriptionColor('success')
                ->iconColor('primary')
                ->progress($totalSalesThisYear / $totalSales * 100)
                ->progressBarColor('success')
                ->chart($yearlySales->toArray()),
        ];
    }
}
