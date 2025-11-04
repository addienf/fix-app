<?php

namespace App\Traits;

trait HasModelFilter
{
    protected function getMonthLabels(): array
    {
        return collect(range(1, 12))
            ->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('M'))
            ->toArray();
    }

    protected function getCacheKey(string $prefix, string $modelKey, string $period): string
    {
        return "{$prefix}-{$modelKey}-{$period}";
    }
}
