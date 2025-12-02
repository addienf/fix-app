<?php

namespace App\Filament\Resources\Warehouse\Peminjaman\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;

trait Peminjaman
{
    use SimpleFormResource, HasAutoNumber;
    protected static function peminjamanSection(): Section
    {
        return Section::make('Peminjaman')
            ->relationship('pic')
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        self::textInput('department', 'Departemen')
                            ->default(fn() => Str::headline(auth()->user()->roles->first()?->name ?? '')),

                        Hidden::make('nama_peminjam')
                            ->default(fn() => auth()->id()),

                        self::textInput('nama_peminjam_placeholder', 'Nama Peminjam')
                            ->default(fn() => auth()->user()?->name)
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        // self::textInput('nama_peminjam', 'Nama Peminjam')
                        //     ->default(auth()->user()->name),
                    ]),
                self::signatureInput('signature', 'Tanda Tangan')->columnSpanFull(),
            ]);
    }
}
