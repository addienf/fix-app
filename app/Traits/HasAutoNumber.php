<?php

namespace App\Traits;

use App\Services\DocumentNumber;
use Filament\Forms\Components\TextInput;

trait HasAutoNumber
{
    public static function newAutoNmberField(string $name, string $label, array $config, bool $readOnly = false): TextInput
    {
        $c = $config + ['prefix' => 'QKS', 'section' => 'GEN', 'type' => 'DOC'];

        return TextInput::make($name)
            ->label($label)
            ->hint("Format: XXX/{$c['prefix']}/{$c['section']}/{$c['type']}/MM/YY")
            ->default(fn() => app(DocumentNumber::class)->next(
                table: $c['table'],
                column: $name,
                type: $c['type'],
                prefix: $c['prefix'],
                section: $c['section'],
            ))
            ->unique(ignoreRecord: true)
            ->required()
            ->readOnly($readOnly);
    }
}
