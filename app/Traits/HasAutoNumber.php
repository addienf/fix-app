<?php

namespace App\Traits;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\DB;

trait HasAutoNumber
{
    public static function autoNumberField(string $name, string $label, array $config): TextInput
    {
        $prefix = $config['prefix'] ?? 'QKS';
        $section = $config['section'] ?? 'GEN';
        $type = $config['type'] ?? 'DOC';
        $table = $config['table'] ?? null;

        return TextInput::make($name)
            ->label($label)
            ->hint("Format: XXX/{$prefix}/{$section}/{$type}/MM/YY")
            ->default(function () use ($table, $prefix, $section, $type, $name) {

                $month = now()->format('m');
                $year = now()->format('y');

                $lastNumber = DB::table($table)
                    ->select(DB::raw("MAX(CAST(SUBSTRING_INDEX({$name}, '/', 1) AS UNSIGNED)) as max_num"))
                    ->first()
                    ->max_num;

                $num = $lastNumber ? $lastNumber + 1 : 1;
                $numStr = str_pad($num, 3, '0', STR_PAD_LEFT);

                return "{$numStr}/{$prefix}/{$section}/{$type}/{$month}/{$year}";
            })
            ->unique()
            ->required()
            ->extraAttributes([
                'readonly' => true,
                'style' => 'pointer-events: none;'
            ]);
    }

    public static function autoNumberField2(string $name, string $label, array $config): TextInput
    {
        $prefix = $config['prefix'] ?? 'QKS';
        $section = $config['section'] ?? 'GEN';
        $type = $config['type'] ?? 'DOC';
        $table = $config['table'] ?? null;

        return TextInput::make($name)
            ->label($label)
            ->hint("Format: XXX/{$prefix}/{$section}/{$type}/MM/YY")
            ->default(function () use ($table, $name, $prefix, $section, $type) {
                if (! $table) return null;

                $month = now()->format('m');
                $year = now()->format('y');

                $last = DB::table($table)
                    ->select($name)
                    ->whereNotNull($name)
                    ->orderByDesc('id')
                    ->first();

                if ($last && preg_match('/^(\d{3})/', $last->$name, $m)) {
                    $num = intval($m[1]) + 1;
                } else {
                    $num = 1;
                }

                $num = str_pad($num, 3, '0', STR_PAD_LEFT);

                return "{$num}/{$prefix}/{$section}/{$type}/{$month}/{$year}";
            })
            ->unique(ignorable: fn($record) => $record)
            ->required()
            ->extraAttributes([
                'readonly' => true,
                'style' => 'pointer-events: none;',
            ]);
    }
}
