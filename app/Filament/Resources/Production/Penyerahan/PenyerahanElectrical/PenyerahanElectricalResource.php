<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical;

use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\Pages;
use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\RelationManagers;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PenyerahanElectricalResource extends Resource
{
    protected static ?string $model = PenyerahanElectrical::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 12;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Serah Terima Electrical';
    protected static ?string $pluralLabel = 'Serah Terima Electrical';
    protected static ?string $modelLabel = 'Serah Terima Electrical';

    public static function getNavigationBadge(): ?string
    {
        $count = PenyerahanElectrical::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                Section::make('Informasi Produk')
                    ->schema([
                        self::selectMaterialID()
                            ->columnSpanFull(),

                        self::textInput('nama_produk', 'Nama Produk')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('kode_produk', 'Kode Produk'),

                        self::textInput('no_seri', 'Nomor Batch/Seri'),

                        self::datePicker('tanggal_selesai', 'Tanggal Produksi Selesai')
                            ->required(),

                        self::textInput('jumlah', 'Jumlah Unit')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::selectKondisi(),

                        self::textArea('deskripsi_kondisi', 'Deskripsi Produk')
                            ->columnSpanFull(),

                    ])->columns(3),

                Split::make([
                    Section::make('Pengecekan Sebelum Serah Terima')
                        ->relationship('sebelumSerahTerima')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    self::selectKondisiFisik(),
                                    self::textArea('detail_kondisi_fisik', 'Detail Kondisi')
                                        ->visible(fn($get) => $get('kondisi_fisik') === 'perlu_perbaikan'),
                                    self::selectKelengkapanDokumen(),
                                    self::textArea('detail_kelengkapan_komponen', 'Detail Kelengkapan Komponen')
                                        ->visible(fn($get) => $get('kelengkapan_komponen') === 'kurang'),
                                    self::selectDokumen(),
                                    TextInput::make('file_pendukung')
                                ])
                                ->columns(1),
                        ]),
                ]),

                Split::make([
                    Section::make('Penerimaan Oleh Produksi Elektrikal')
                        ->relationship('penerimaElectrical')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    self::datePicker('tanggal', 'Tanggal Serah Terima'),
                                    self::textInput('diterima_oleh', 'DIterima Oleh (Nama & Jabatan)'),
                                    self::textArea('catatan_tambahan', 'Catatan Tambahan'),
                                    self::selectStatusPenerimaan()
                                ])
                                ->columns(1),
                        ]),
                ]),

                // Section::make('PIC')
                //     ->relationship('pic')
                //     ->schema([
                //         Grid::make(3)
                //             ->schema([
                //                 self::textInput('submit_name', 'Diserahkan Oleh')
                //                     ->required(),
                //                 self::textInput('receive_name', 'Diterima Oleh')
                //                     ->required(),
                //                 self::textInput('knowing_name', 'Diketahui Oleh')
                //                     ->required(),
                //                 self::signatureInput('submit_signature', '')
                //                     ->required(),
                //                 self::signatureInput('receive_signature', '')
                //                     ->required(),
                //                 self::signatureInput('knowing_signature', '')
                //             ])
                //     ]),

                Section::make('Detail PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Grid::make(1)
                                    ->schema([

                                        self::textInput('submit_name', 'Submited By'),

                                        self::signatureInput('submit_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('receive_name', 'Received By'),

                                        self::signatureInput('receive_signature', ''),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || filled($record?->receive_signature)
                                    ),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('knowing_name', 'Knowing By'),

                                        self::signatureInput('knowing_signature', ''),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || blank($record?->receive_signature) || filled($record?->knowing_signature)
                                    ),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('nama_produk', 'Nama Produk'),

                TextColumn::make('status_penyelesaian')
                    ->label('Status Penyelesaian')
                    ->badge()
                    ->color(function ($record) {
                        $penyelesaian = $record->status_penyelesaian;
                        $persetujuan = $record->status_persetujuan;

                        if ($penyelesaian === 'Disetujui') {
                            return 'success';
                        }

                        if ($penyelesaian !== 'Diterima' && $persetujuan !== 'Disetujui') {
                            return 'danger';
                        }

                        return 'warning';
                    })
                    ->alignCenter(),

                ImageColumn::make('pic.submit_signature')
                    ->width(150)
                    ->label('Submited')
                    ->height(75),

                ImageColumn::make('pic.receive_signature')
                    ->width(150)
                    ->label('Received')
                    ->height(75),

                ImageColumn::make('pic.knowing_signature')
                    ->width(150)
                    ->label('Knowing')
                    ->height(75),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenyerahanElectricals::route('/'),
            'create' => Pages\CreatePenyerahanElectrical::route('/create'),
            'edit' => Pages\EditPenyerahanElectrical::route('/{record}/edit'),
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function selectInput(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
            ->relationship($relation, $title)
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
    }

    protected static function selectMaterialID(): Select
    {
        return
            Select::make('pengecekan_material_id')
            ->label('Pengecekan Material')
            ->required()
            ->reactive()
            ->options(function () {
                return PengecekanMaterialSS::with('spk')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $noUrs = $item->spk->no_spk ?? '-';
                        return [$item->id => "{$item->id} - {$noUrs}"];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pengecekan = PengecekanMaterialSS::with('spk.SpesifikasiProduct.details.product')
                    ->find($state);

                $pengecekan2 = PengecekanMaterialSS::with('spk.jadwalProduksi')
                    ->find($state);

                if (!$pengecekan || !$pengecekan->spk) return;

                if (!$pengecekan || !$pengecekan2->spk) return;

                $spesifikasi = $pengecekan->spk->SpesifikasiProduct;
                $selesai = $pengecekan2->spk->jadwalProduksi;

                if (!$spesifikasi || !$selesai) return;

                $namaProduk = $spesifikasi->details->first()?->product?->name ?? '-';
                $jumlah = $spesifikasi->details->first()?->quantity ?? '-';
                $tgl_selesai = $selesai->details->first()?->tanggal_selesai ?? '-';

                $set('nama_produk', $namaProduk);
                $set('jumlah', $jumlah);
                $set('tanggal_selesai', \Carbon\Carbon::parse($tgl_selesai));
            });
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
                'cukup baik' => 'Cukup Baik',
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
                'perlu diganti' => 'Ada Komponen Yang Perlu Diperbaiki atau Diganti'
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
                'gambar teknis' => 'Gambar Teknis',
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

    protected static function datePicker(string $fieldName, string $label): DatePicker
    {
        return
            DatePicker::make($fieldName)
            ->label($label)
            ->displayFormat('M d Y')
            ->seconds(false);
    }

    protected static function signatureInput(string $fieldName, string $labelName): SignaturePad
    {
        return
            SignaturePad::make($fieldName)
            ->label($labelName)
            ->exportPenColor('#0118D8')
            ->helperText('*Harap Tandatangan di tengah area yang disediakan.')
            ->afterStateUpdated(function ($state, $set) use ($fieldName) {
                if (blank($state))
                    return;
                $path = SignatureUploader::handle($state, 'ttd_', 'Production/PenyerahanElectrical/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }

    protected static function textArea(string $fieldName, string $label): Textarea
    {
        return
            Textarea::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function textColumn(string $fieldName, string $label): TextColumn
    {
        return
            TextColumn::make($fieldName)
            ->label($label)
            ->searchable()
            ->sortable();
    }
}
