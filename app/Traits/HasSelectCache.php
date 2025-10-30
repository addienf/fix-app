<?php

namespace App\Traits;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait HasSelectCache
{
    /**
     * @property class-string<\Illuminate\Database\Eloquent\Model> $model
     */
    protected static function selectInputCache(
        string $fieldName,
        string $label,
        string $relation,
        string $title,
        int $limit = 10,
        int $cacheDuration = 60
    ): Select {
        $resourceModel = static::$model;
        $relatedModel = (new $resourceModel)->$relation()->getModel();

        $cacheKey = "select_options_{$relation}_{$title}_limit_{$limit}";

        return Select::make($fieldName)
            ->label($label)
            ->options(function () use ($relatedModel, $title, $cacheKey, $cacheDuration, $limit) {
                // Preload data terbatas
                return Cache::remember($cacheKey, $cacheDuration, function () use ($relatedModel, $title, $limit) {
                    return $relatedModel::query()
                        ->orderBy($title)
                        ->limit($limit)
                        ->pluck($title, 'id')
                        ->toArray();
                });
            })
            ->getSearchResultsUsing(function (string $search) use ($relatedModel, $title, $cacheKey) {
                // Query pencarian (tidak dibatasi preload)
                $searchKey = "{$cacheKey}_search_" . md5($search);
                return Cache::remember($searchKey, 30, function () use ($relatedModel, $title, $search) {
                    return $relatedModel::query()
                        ->where($title, 'like', "%{$search}%")
                        ->orderBy($title)
                        ->limit(50)
                        ->pluck($title, 'id')
                        ->toArray();
                });
            })
            ->getOptionLabelUsing(fn($value): ?string => $relatedModel::find($value)?->$title)
            ->searchable()
            ->preload()
            ->native(false)
            ->required();
    }

    /**
     * Hapus cache select berdasarkan konfigurasi.
     */
    public static function clearSelectCache(string $relation, string $title, int $limit = 10): void
    {
        // Hapus cache preload
        $cacheKey = "select_options_{$relation}_{$title}_limit_{$limit}";
        Cache::forget($cacheKey);

        // Hapus cache search juga
        foreach (Cache::getMultiple(range(1, 5)) as $key => $value) {
            if (str_starts_with($key, "{$cacheKey}_search_")) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Hook otomatis untuk hapus cache setelah save / delete.
     */
    protected static function bootHasSelectCache(): void
    {
        static::saved(function ($model) {
            if (method_exists($model, 'getSelectCacheConfig')) {
                $configs = $model->getSelectCacheConfig();
                foreach ((array) $configs as $config) {
                    static::clearSelectCache($config['relation'], $config['title'], $config['limit'] ?? 10);
                }
            }
        });

        static::deleted(function ($model) {
            if (method_exists($model, 'getSelectCacheConfig')) {
                $configs = $model->getSelectCacheConfig();
                foreach ((array) $configs as $config) {
                    static::clearSelectCache($config['relation'], $config['title'], $config['limit'] ?? 10);
                }
            }
        });
    }
}
