<?php

namespace App\Traits;

use App\Services\DocumentNumber;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\DB;

trait HasAutoNumber
{
    public static function newAutoNmberField(string $name, string $label, array $config, bool $readOnly = false): TextInput
    {
        $c = $config + ['prefix' => 'QKS', 'section' => 'GEN', 'type' => 'DOC'];

        return TextInput::make($name)
            ->label($label)
            ->hint("Format: XXX/{$c['prefix']}/{$c['section']}/{$c['type']}/MM/YY")
            ->default(fn() => DocumentNumber::next($c['table'], $name, $c['type'], $c['prefix'], $c['section']))
            ->unique(ignoreRecord: true)
            ->required()
            ->readOnly($readOnly);
    }
}
