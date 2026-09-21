<?php

namespace App\Filament\Resources\MR\Perubahan\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait DokumenPerubahan
{
    use SimpleFormResource;
    protected static function dokumenPerubahan(): Section
    {
        return
            Section::make('Dokumen Perubahan')
            ->relationship('dokPerubahan')
            ->collapsible()
            ->schema([
                Grid::make(3)
                    ->schema([
                        self::textInput('nomor_dok_lama', 'Nomor Dokumen Lama'),
                        self::textInput('nomor_rev_lama', 'Nomor Revisi Lama'),
                        self::textInput('judul_dok_lama', 'Judul Dokumen Lama'),
                    ]),
                Grid::make(3)
                    ->schema([
                        self::textInput('nomor_dok_baru', 'Nomor Dokumen Baru'),
                        self::textInput('nomor_rev_baru', 'Nomor Revisi Baru'),
                        self::textInput('judul_dok_baru', 'Judul Dokumen Baru'),
                    ]),

                self::textInput('dokumen_terkait', 'Dokumen Terkait Yang Direvisi'),
                self::textareaInput('uraian_perubahan', 'Uraian Perubahan')
            ]);
    }
}
