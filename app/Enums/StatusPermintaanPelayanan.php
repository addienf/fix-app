<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusPermintaanPelayanan: string implements HasLabel, HasColor
{
    case BelumDiterima = 'Belum Diterima';
    case Diterima = 'Diterima';
    case Diketahui = 'Diketahui';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BelumDiterima => 'danger',
            self::Diterima => 'warning',
            self::Diketahui => 'success',
        };
    }
}
