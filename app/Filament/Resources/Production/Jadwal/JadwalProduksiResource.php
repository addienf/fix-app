<?php

namespace App\Filament\Resources\Production\Jadwal;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;
use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\RelationManagers;
use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class JadwalProduksiResource extends Resource
{
    protected static ?string $model = JadwalJadwalProduksi::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Jadwal Produksi';
    protected static ?string $pluralLabel = 'Jadwal Produksi';
    protected static ?string $modelLabel = 'Jadwal Produksi';
    protected static ?string $slug = 'produksi/jadwal-produksi';

    public static function getNavigationBadge(): ?string
    {
        $count = JadwalJadwalProduksi::where('status_persetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_persetujuan')
                    ->default('Belum Disetujui'),

                Section::make('Informasi Umum')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                self::datePicker('tanggal', 'Tanggal')
                                    ->required(),

                                self::textInput('pic_name', 'Penanggung Jawab'),

                                self::textInput('no_surat', 'No Surat'),

                                self::selectInput('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                                    ->hiddenOn('edit')
                                    ->placeholder('Pilih Nomor SPK')
                            ])

                    ]),

                Section::make('Identifikasi Produk')
                    ->schema([
                        TableRepeater::make('identifikasiProduks')
                            ->relationship('identifikasiProduks')
                            ->label('')
                            ->schema([

                                self::textInput('nama_alat', 'Nama Alat'),

                                self::textInput('tipe', 'Tipe/Model'),

                                self::textInput('no_seri', 'Nomor Seri'),

                                self::textInput('custom_standar', 'Custom/Stardar'),

                                self::textInput('jumlah', 'Quantity')->numeric(),

                            ])
                            ->deletable(true)
                            ->addable(true)
                            ->reorderable(false)
                            ->columnSpanFull(),

                    ]),

                Section::make('Detail Jadwal Produksi')
                    ->schema([
                        TableRepeater::make('details')
                            ->relationship('details')
                            ->label('')
                            ->schema([

                                self::textInput('pekerjaan', 'Pekerjaan'),

                                self::textInput('pekerja', 'Yang Mengerjakan'),

                                self::datePicker('tanggal_mulai', 'Tanggal Mulai')
                                    ->required(),

                                self::datePicker('tanggal_selesai', 'Tanggal Selesai')
                                    ->required(),
                            ])
                            ->deletable(true)
                            ->addable(true)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Standard')
                    ->schema([
                        FileUpload::make('file_upload')
                            ->label('File Pendukung')
                            ->directory('Production/Jadwal/Files')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Drawing wajib dilampirkan'),
                    ]),

                Section::make('Kebutuhan bahan/alat')
                    ->schema([
                        TableRepeater::make('sumbers')
                            ->relationship('sumbers')
                            ->label('')
                            ->schema([

                                self::textInput('bahan_baku', 'Nama Bahan Baku'),

                                self::textInput('spesifikasi', 'Spesifikasi'),

                                self::textInput('jumlah', 'Quantity'),

                                self::textInput('status', 'Status (Diterima atau Belum)'),

                                self::textInput('keperluan', 'Keperluan'),
                            ])
                            ->deletable(true)
                            ->reorderable(false)
                            ->addable(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('Timeline Produksi')
                    ->schema([
                        TableRepeater::make('timelines')
                            ->relationship('timelines')
                            ->label('')
                            ->schema([

                                self::textInput('task', 'Task'),

                                self::datePicker('tanggal_mulai', 'Tanggal Mulai')
                                    ->required(),

                                self::datePicker('tanggal_selesai', 'Tanggal Selesai')
                                    ->required(),
                            ])
                            ->deletable(true)
                            ->addable(true)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),


                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([
                                        Hidden::make('create_name')
                                            ->default(fn() => auth()->id()),

                                        self::textInput('create_name_placeholder', 'Dibuat Oleh')
                                            ->default(fn() => auth()->user()?->name)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::signatureInput('create_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([
                                        Hidden::make('approve_name')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        self::textInput('approve_name_placeholder', 'Disetujui Oleh')
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->required(false)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::signatureInput('approve_signature', ''),

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
                self::textColumn('spk.no_spk', 'No SPK'),

                self::textColumn('pic_name', 'Nama PIC'),

                self::textColumn('tanggal', 'Tanggal Dibuat')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d M Y')),

                TextColumn::make('status_persetujuan')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Disetujui' ? 'success' : 'danger'
                    )
                    ->alignCenter(),

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionsActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_persetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.jadwalProduksi', ['record' => $record->id])),
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
            'index' => Pages\ListJadwalProduksis::route('/'),
            'create' => Pages\CreateJadwalProduksi::route('/create'),
            'edit' => Pages\EditJadwalProduksi::route('/{record}/edit'),
            'pdfJadwalProduksi' => Pages\pdfJadwalProduksi::route('/{record}/pdfJadwalProduksi')
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
            ->relationship(
                $relation,
                $title,
                fn($query) => $query->where('status_penerimaan', 'Diterima')->whereDoesntHave('jadwalProduksi')
            )
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $spk = SPKMarketing::with('spesifikasiProduct.details.product')->find($state);

                if (!$spk)
                    return;

                $products = $spk->spesifikasiProduct?->details?->map(function ($detail) {
                    return [
                        'nama_produk' => $detail->product?->name ?? '',
                        'jumlah' => $detail->quantity ?? 0,
                    ];
                })->toArray();

                $set('details', $products);
            });
    }

    protected static function datePicker(string $fieldName, string $label): DatePicker
    {
        return
            DatePicker::make($fieldName)
            ->label($label)
            ->displayFormat('M d Y')
            // ->placeholder('Masukan Tanggal')
            // ->native(false)
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Production/Jadwal/Signatures');
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
