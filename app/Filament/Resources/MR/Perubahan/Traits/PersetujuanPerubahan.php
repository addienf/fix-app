<?php

namespace App\Filament\Resources\MR\Perubahan\Traits;

use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait PersetujuanPerubahan
{
    use SimpleFormResource, HasSignature;
    protected static function persetujuanPerubahan(): Fieldset
    {
        return
            Fieldset::make('Persetujuan')
            ->relationship('persetujuanPerubahan')
            ->label('')
            ->schema([
                Section::make('Penanggung Jawab Department')
                    ->hiddenOn('edit')
                    ->schema([
                        self::getPersetujuanPIC(),

                        self::textInput('alasan_penolakan_pic', 'Alasan Penolakan')
                            ->visible(fn($get) => $get('persetujuan_pic') === 'ditolak'),

                        self::dateInput('signature_pic_date', 'Tanggal'),

                        self::signatureInput('signature_pic', '', 'MR/Perubahan/PIC')
                            ->required(),
                    ]),

                Section::make('Wakil Manajemen')
                    ->visible(
                        fn(string $operation) =>
                        $operation !== 'create'
                            && auth()->user()?->hasAnyRole(['super_admin', 'MR'])
                    )
                    ->schema([
                        self::getPersetujuanManajemen(),

                        self::textInput('alasan_penolakan_manajemen', 'Alasan Penolakan')
                            ->visible(fn($get) => $get('persetujuan_manajemen') === 'ditolak'),

                        self::dateInput('signature_manajemen_date', 'Tanggal'),

                        self::signatureInput('signature_manajemen', '', 'MR/Perubahan/Manajemen')
                            ->required(),
                    ])
            ]);
    }

    private static function getPersetujuanPIC()
    {
        return
            ButtonGroup::make('persetujuan_pic')
            ->label('Metode Tanda Tangan')
            ->options([
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
            ])
            ->live()
            ->required()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }

    private static function getPersetujuanManajemen()
    {
        return
            ButtonGroup::make('persetujuan_manajemen')
            ->label('Metode Tanda Tangan')
            ->options([
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
            ])
            ->live()
            ->required()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }
}
