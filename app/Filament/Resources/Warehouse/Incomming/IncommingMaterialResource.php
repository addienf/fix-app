<?php

namespace App\Filament\Resources\Warehouse\Incomming;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;
use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\RelationManagers;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class IncommingMaterialResource extends Resource
{
    protected static ?string $model = IncommingMaterial::class;
    protected static ?string $slug = 'warehouse/incoming-material';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Incoming Material';
    protected static ?string $pluralLabel = 'Incoming Material';
    protected static ?string $modelLabel = 'Incoming Material';

    public static function getNavigationBadge(): ?string
    {
        $count = IncommingMaterial::where('status_penerimaan_pic', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penerimaan_pic')
                    ->default('Belum Diterima'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([

                        self::selectInput()
                            ->placeholder('Pilih Nomor Surat Permintaan Bahan')
                            ->hiddenOn('edit')
                            ->required(),

                        self::datePicker('tanggal', 'Tanggal Penerimaan')
                            ->required(),

                    ]),

                Section::make('Informasi Material')
                    ->collapsible()
                    ->schema([

                        Repeater::make('details')
                            ->relationship('details')
                            ->schema([

                                Grid::make(6)
                                    ->schema([

                                        self::textInput('nama_material', 'Nama Material')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::textInput('batch_no', 'Batch No')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::textInput('jumlah', 'Jumlah Diterima')
                                            ->numeric(),

                                        self::textInput('satuan', 'Satuan'),

                                        self::textInput('kondisi_material', 'Kondisi Material'),

                                        self::selectStatusLabel(),
                                    ]),

                            ])
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false),

                    ]),

                Section::make('Keterangan')
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
                    ]),

                Section::make('Detail PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('submited_name')
                                            ->default(fn() => auth()->id()),

                                        self::textInput('submited_name_placeholder', 'Diserahkan Oleh')
                                            ->default(fn() => auth()->user()?->name)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('submited_name', 'Diserahkan Oleh'),

                                        self::signatureInput('submited_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('received_name')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        self::textInput('received_name_placeholder', 'Diterima Oleh')
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->required(false)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('received_name', 'Diterima Oleh'),

                                        self::signatureInput('received_signature', ''),

                                    ])->hiddenOn(operations: 'create'),

                            ]),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('permintaanPembelian.permintaanBahanWBB.no_surat')
                    ->label('No Surat Permintaan Bahan'),

                self::textColumn('tanggal', 'Tanggal Penerimaan')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d M Y')),

                TextColumn::make('status_penerimaan_pic')
                    ->label('Status Penerimaan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Diterima' ? 'success' : 'danger'
                    )
                    ->alignCenter(),

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penerimaan_pic === 'Diterima')
                        ->url(fn($record) => route('pdf.IncomingMaterial', ['record' => $record->id])),
                ])
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
            'index' => Pages\ListIncommingMaterials::route('/'),
            'create' => Pages\CreateIncommingMaterial::route('/create'),
            'edit' => Pages\EditIncommingMaterial::route('/{record}/edit'),
            'pdfIncommingMaterial' => Pages\pdfIncommingMaterial::route('/{record}/pdfIncommingMaterial')
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function selectInput(): Select
    {
        return
            Select::make('permintaan_pembelian_id')
            ->label('Permintaan Pembelian')
            ->options(function () {
                return PermintaanPembelian::with('permintaanBahanWBB')
                    ->whereDoesntHave('incommingMaterial')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $noSurat = $item->permintaanBahanWBB->no_surat ?? '-';
                        return [$item->id => "{$item->id} - {$noSurat}"];
                    });
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pembelian = PermintaanPembelian::with(
                    'permintaanBahanWBB',
                    'details',
                    'materialSS',
                    'materialNonSS'
                )->find($state);

                // $namaBarangList = $pembelian->details?->pluck('nama_barang');
                // dd($namaBarangList);

                if (!$pembelian) return;

                $no_batch = $pembelian?->materialNonSS?->batch_no;

                $details = $pembelian->details->map(function ($detail) use ($no_batch) {
                    return [
                        'nama_material' => $detail->nama_barang ?? '-',
                        'jumlah' => $detail->jumlah ?? '-',
                        'batch_no' => $no_batch ?? '-',
                    ];
                })->toArray();

                // dd($details);

                $set('details', $details);
            });
    }

    protected static function selectInputOptions(string $fieldName, string $label, string $config): Select
    {
        return
            Select::make($fieldName)
            ->options(config($config))
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
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

    protected static function selectStatusLabel(): Select
    {
        return
            Select::make('status_qc')
            ->label('Status Label QC')
            ->required()
            ->reactive()
            ->placeholder('Pilih Status Label QC')
            ->options([
                1 => 'Ada',
                0 => 'Tidak Ada',
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Warehouse/IncommingMaterial/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
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
