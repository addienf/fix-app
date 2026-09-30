<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DocumentNumber
{
    private const ROMAN = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];

    public static function next(
        string $table,
        string $column,
        string $type,
        string $prefix = 'QKS',
        string $section = 'WBB',
    ): string {
        $suffix = sprintf(
            '%s/%s/%s/%s/%s',
            $prefix,
            $section,
            $type,
            self::ROMAN[now()->month],
            now()->format('y')
        );

        $last = DB::table($table)
            ->where($column, 'like', "%/{$suffix}")
            ->orderByRaw("CAST(SUBSTRING_INDEX({$column}, '/', 1) AS UNSIGNED) DESC")
            ->value($column);

        $next = $last ? ((int) strtok($last, '/')) + 1 : 1;

        return sprintf('%03d/%s', $next, $suffix);
    }
}
