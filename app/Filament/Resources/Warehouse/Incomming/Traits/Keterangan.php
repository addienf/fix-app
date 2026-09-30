<?php

namespace App\Filament\Resources\Warehouse\Incomming\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait Keterangan
{
    use SimpleFormResource, HasAutoNumber;
    protected static function keteranganSection(): Section
    {
        return
            Section::make('Keterangan')
            ->collapsible()
            ->schema([

                Grid::make([
                    'default' => 1,
                    'md' => 3,
                    'lg' => 3,
                ])
                    ->schema([

                        self::selectPemeriksaanMaterial()
                            ->helperText('Apakah material dalam kondisi baik? (Ya/Tidak)'),

                        self::selectStatusPenerimaan(),

                        self::selectDokumenPendukung(),

                    ]),

                FileUpload::make('file_upload')
                    ->label('Upload Dokumen')
                    ->directory('Warehouse/IncommingMaterial/Files')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.')
                    ->visible(fn($get) => (int) $get('dokumen_pendukung') === 1),
            ]);
    }

    protected static function selectPemeriksaanMaterial(): Select
    {
        return static::yesNoSelect('kondisi_material', 'Pemeriksaan Material');
    }

    protected static function selectStatusPenerimaan(): Select
    {
        return static::yesNoSelect('status_penerimaan', 'Status Penerimaan', [
            1 => 'Diterima',
            0 => 'Ditolak dan dikembalikan',
        ]);
    }

    protected static function selectDokumenPendukung(): Select
    {
        return static::yesNoSelect('dokumen_pendukung', 'Dokumen Pendukung')->live();
    }

    protected static function yesNoSelect(
        string $name,
        string $label,
        array $options = [1 => 'Ya', 0 => 'Tidak'],
    ): Select {
        return Select::make($name)
            ->label($label)
            ->placeholder("Pilih {$label}")
            ->options($options)
            ->required();
    }
}
