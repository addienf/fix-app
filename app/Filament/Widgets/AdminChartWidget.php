<?php

namespace App\Filament\Widgets;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class AdminChartWidget extends AdvancedChartWidget
{
    protected static ?string $heading = null;
    protected static string $color = 'info';
    protected static ?string $icon = 'heroicon-o-chart-bar';
    protected static ?string $iconColor = 'info';
    protected static ?string $iconBackgroundColor = 'info';
    protected static ?string $label = null;

    protected function getFilters(): ?array
    {
        return [
            'spec' => 'Spesifikasi Produk',
            'spk' => 'SPK Marketing',
        ];
    }

    public function getHeading(): ?string
    {
        $filter = $this->filter ?? 'all';

        $totalSpec = SpesifikasiProduct::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $totalSPK = SPKMarketing::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        return match ($filter) {
            'spk' => "$totalSPK",
            'spec' => "$totalSpec",
            default => "$totalSpec",
        };
    }

    public function getLabel(): ?string
    {
        $filter = $this->filter ?? 'all';

        return match ($filter) {
            'spk' => 'Total Data SPK Marketing Bulan Ini',
            'spec' => 'Total Data Spesifikasi Product Bulan Ini',
            default => 'Total Data Spesifikasi Product Bulan Ini',
        };
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'spec';

        $query = match ($filter) {
            'spec' => SpesifikasiProduct::query(),
            'spk' => SPKMarketing::query(),
            default => SpesifikasiProduct::query(),
        };

        $data = Trend::query($query)
            ->between(now()->startOfMonth(), now()->endOfMonth())
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => match ($filter) {
                        'spec' => 'Spesifikasi Produk',
                        'spk' => 'SPK Marketing',
                        default => 'Spesifikasi Produk',
                    },
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('d M')),
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
