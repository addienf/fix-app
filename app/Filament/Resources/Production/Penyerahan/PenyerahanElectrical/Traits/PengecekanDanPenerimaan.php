<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\Traits;

use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Cache;

trait PengecekanDanPenerimaan
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getPengecekanDanPenerimaanSection()
    {
        // $isEdit = $form->getOperation() === 'edit';

        return
            Fieldset::make('Pengecekan dan Penerimaan')
            ->schema([
                Split::make([
                    Section::make('Pengecekan Sebelum Serah Terima')
                        ->relationship('sebelumSerahTerima')
                        ->collapsible()
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    self::selectKondisiFisik(),

                                    self::textareaInput('detail_kondisi_fisik', 'Detail Kondisi')
                                        ->visible(fn($get) => $get('kondisi_fisik') === 'perlu_perbaikan'),

                                    self::selectKelengkapanDokumen(),

                                    self::textareaInput('detail_kelengkapan_komponen', 'Detail Kelengkapan Komponen')
                                        ->visible(fn($get) => $get('kelengkapan_komponen') === 'kurang'),

                                    self::selectDokumen(),

                                    // TextInput::make('file_pendukung'),
                                    // FileUpload::make('file_pendukung')
                                    //     ->label('File Pendukung')
                                    //     ->directory('Production/PenyerahanElectrical/Files')
                                    //     ->acceptedFileTypes(['application/pdf'])
                                    //     ->maxSize(10240)
                                    //     ->required()
                                    //     ->columnSpanFull()
                                    //     ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.'),

                                    self::uploadField(
                                        'file_pendukung',
                                        'File Pendukung',
                                        'Production/PenyerahanElectrical/Files',
                                        'Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.',
                                        types: ['application/pdf'],
                                        maxSize: 10240,
                                    ),
                                ])
                                ->columns(1),
                        ]),
                ]),

                Split::make([
                    Section::make('Penerimaan Oleh Produksi Elektrikal')
                        ->relationship('penerimaElectrical')
                        ->collapsible()
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    self::dateInput('tanggal', 'Tanggal Serah Terima'),
                                    self::textInput('diterima_oleh', 'DIterima Oleh (Nama & Jabatan)'),
                                    self::textareaInput('catatan_tambahan', 'Catatan Tambahan'),
                                    self::selectStatusPenerimaan()
                                ])
                                ->columns(1),
                        ]),
                ]),
            ]);
    }

    protected static function selectKondisi(): Select
    {
        return
            Select::make('kondisi')
            ->label('Kondisi Produk')
            ->required()
            ->placeholder('Pilih Kondisi Produk')
            ->options([
                'baik' => 'Baik',
                'cukup_baik' => 'Cukup Baik',
                'perlu_perbaikan' => 'Perlu Perbaikan'
            ]);
    }

    protected static function selectKondisiFisik(): Select
    {
        return
            Select::make('kondisi_fisik')
            ->label('Kondisi Fisik Produk')
            ->required()
            ->reactive()
            ->placeholder('Pilih Kondisi Fisik Produk')
            ->options([
                'baik' => 'Tidak Ada Kerusakan Fisik',
                'cukup_baik' => 'Ada Sedikit Cacat Visual',
                'perlu_perbaikan' => 'Ada Kerusakan Signifikan'
            ]);
    }

    protected static function selectKelengkapanDokumen(): Select
    {
        return
            Select::make('kelengkapan_komponen')
            ->label('Kelengkapan Komponen')
            ->required()
            ->reactive()
            ->placeholder('Pilih Kelengkapan Komponen')
            ->options([
                'semua' => 'Semua Komponen Mekanin Terpasang Dengan Benar',
                'kurang' => 'Ada Komponen Yang Kurang',
                'perlu_diganti' => 'Ada Komponen Yang Perlu Diperbaiki atau Diganti'
            ]);
    }

    protected static function selectDokumen(): Select
    {
        return
            Select::make('dokumen_pendukung')
            ->label('Dokumen Pendukung')
            ->required()
            ->placeholder('Pilih Dokumen Pendukung')
            ->options([
                'gambar_teknis' => 'Gambar Teknis',
                'sop' => 'SOP atau Instruksi Perakitan',
                'laporan' => 'Laporan QC (Quality Control)'
            ]);
    }

    protected static function selectStatusPenerimaan(): Select
    {
        return
            Select::make('status_penerimaan')
            ->label('Status Penerimaan')
            ->required()
            ->placeholder('Pilih Status Penerimaan')
            ->options([
                'diterima' => 'Diterima Tanpa Catatan',
                'catatan' => 'Diterima Dengan Catatan',
                'ditolak' => 'Ditolak dan Dikembalikan ke Divisi Mekanik'
            ]);
    }
}
