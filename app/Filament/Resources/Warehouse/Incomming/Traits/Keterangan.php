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
        return Section::make('Keterangan')
            ->collapsible()
            ->schema([

                Grid::make(3)
                    ->schema([

                        self::selectPemeriksaanMaterial()
                            ->helperText('Apakah material dalam kondisi baik? (Ya/Tidak)'),

                        self::selectStatusPenerimaan(),

                        self::selectDokumenPendukung(),

                    ]),

                FileUpload::make('file_upload')
                    ->label('Upload Dokumen')
                    ->directory('Sales/Spesifikasi/Files')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.')
                    ->visible(fn($get) => $get('dokumen_pendukung') === '1'),
            ]);
    }

    protected static function selectPemeriksaanMaterial(): Select
    {
        return
            Select::make('kondisi_material')
            ->label('Pemeriksaan Material')
            ->required()
            ->reactive()
            ->placeholder('Pilih Hasil Pemeriksaan Material')
            ->options([
                1 => 'Ya',
                0 => 'Tidak',
            ]);
    }

    protected static function selectStatusPenerimaan(): Select
    {
        return
            Select::make('status_penerimaan')
            ->label('Status Penerimaan')
            ->required()
            ->reactive()
            ->placeholder('Pilih Status Penerimaan')
            ->options([
                1 => 'Diterima',
                0 => 'Ditolak dan dikembalikan',
            ]);
    }

    protected static function selectDokumenPendukung(): Select
    {
        return
            Select::make('dokumen_pendukung')
            ->label('Dokumen Pendukung')
            ->required()
            ->reactive()
            ->placeholder('Tambahkan Dokumen Pendukung')
            ->options([
                1 => 'Ya',
                0 => 'Tidak',
            ]);
    }
}
