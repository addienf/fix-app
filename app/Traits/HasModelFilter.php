<?php

namespace App\Traits;

use App\Models\User;

trait HasModelFilter
{
    protected function getSelectedModelConfig(string $defaultModel = 'user'): array
    {
        $selectedModel = $this->filters['selectedModel'] ?? $defaultModel;
        $config = config("models.$selectedModel", []);

        return [
            'key' => $selectedModel,
            'label' => $config['label'] ?? ucfirst($selectedModel),
            'class' => $config['model'] ?? User::class,
        ];
    }

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
