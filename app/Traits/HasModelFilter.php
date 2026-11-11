<?php

namespace App\Traits;

use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;

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
    public function isVisible(): bool
    {
        $selectedDepartment = $this->filters['selectedDepartment'] ?? null;
        $selectedModel = $this->filters['selectedModel'] ?? null;
        return $selectedDepartment
            && $selectedModel
            && config("models.$selectedDepartment.$selectedModel");
    }
    protected function getSelectedModelConfig(string $department, string $modelKey): ?array
    {
        $config = config("models.$department.$modelKey");
        if (! $config || ! isset($config['model'])) {
            return null;
        }
        return [
            'key'   => $modelKey,
            'label' => $config['label'],
            'class' => $config['model'],
        ];
    }
    public static function getUsedSpkTagKeys()
    {
        return collect()
            ->merge(static::mapUsedKeys(WalkinChamber::class))
            ->merge(static::mapUsedKeys(ChamberR2::class))
            ->merge(static::mapUsedKeys(Refrigerator::class))
            ->merge(static::mapUsedKeys(ColdRoom::class))
            ->merge(static::mapUsedKeys(RissingPipette::class))
            ->merge(static::mapUsedKeys(ChamberWalkinG2::class))
            ->merge(static::mapUsedKeys(ChamberG2::class))
            ->filter()
            ->unique();
    }
    private static function mapUsedKeys(string $model)
    {
        // Hanya ambil kolom yang dipakai -> hemat memori hingga 90%
        return $model::query()
            ->select(['id', 'spk_service_id', 'tag_no'])
            ->with(['spkService:id,no_spk_service'])
            ->get()
            ->map(
                fn($item) => $item->spkService?->no_spk_service . '|' . trim($item->tag_no)
            );
    }
}
