<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;

trait SyaratDanketentuan
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getSyaratDanketentuanSection()
    {
        return
            Section::make('C. Syarat dan Ketentuan')
            ->relationship('snk')
            ->schema([
                self::textareaInput('penyebab', '1. Penyebab Ketidaksesuaian')->rows(1),
                self::textareaInput('tindakan_kolektif', '2. Tindakan Kolektif')->rows(1),
                self::textareaInput('tindakan_pencegahan', '3. Penyebab Pencegahan')->rows(1),
            ])->collapsible();
    }
}
