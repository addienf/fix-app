<?php

namespace App\Filament\Resources\Production\Jadwal;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;
use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\RelationManagers;
use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class JadwalProduksiResource extends Resource
{
    protected static ?string $model = JadwalJadwalProduksi::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Jadwal Produksi';
    protected static ?string $pluralLabel = 'Jadwal Produksi';
    protected static ?string $modelLabel = 'Jadwal Produksi';
    protected static ?string $slug = 'production/jadwal-produksi';

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
                                self::textInput('pic_name', 'Penanggung Jawab'),
                                self::datePicker('tanggal', 'Tanggal')
                                    ->required(),
                            ])
                    ]),

                Section::make('Detail Jadwal Produksi')
                    ->schema([
                        self::selectInput('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                            ->columnSpanFull(),
                        Repeater::make('details')
                            ->relationship('details')
                            ->label('')
                            ->schema([
                                Grid::make(6)
                                    ->schema([
                                        self::textInput('nama_produk', 'Nama Produk')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),
                                        self::textInput('tipe', 'Tipe/Model'),
                                        self::textInput('volume', 'Volume'),
                                        self::textInput('jumlah', 'Jumlah')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),
                                        self::datePicker('tanggal_mulai', 'Tanggal Mulai')
                                            ->required(),
                                        self::datePicker('tanggal_selesai', 'Tanggal Selesai')
                                            ->required()
                                    ])
                            ])
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Sumber Daya Yang Digunakan')
                    ->relationship('sumber')
                    ->schema([
                        self::textInput('mesin_yang_digunakan', 'Mesin Yang Digunakan'),
                        self::textInput('tenaga_kerja', 'Tenaga Kerja'),
                        TableRepeater::make('bahan_baku')
                            ->label('')
                            ->schema([

                                self::textInput('bahan_baku', 'Masukkan Bahan Baku')
                                    ->extraInputAttributes(['class' => 'font-normal']),

                            ])
                            // ->columnSpanFull()
                            ->addActionLabel('Tambah Bahan Baku')
                            ->reorderable(false)
                            ->defaultItems(1)
                    ]),

                Section::make('Detail PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        self::textInput('create_name', 'PIC Pembuat'),
                                        self::signatureInput('create_signature', 'Dibuat Oleh'),
                                    ])->hiddenOn(operations: 'edit'),
                                Grid::make(1)
                                    ->schema([
                                        self::textInput('approve_name', 'PIC Penerima'),
                                        self::signatureInput('approve_signature', 'Diterima Oleh'),
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
                self::textColumn('tanggal', 'Tanggal Dibuat'),
                TextColumn::make('status_persetujuan')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Disetujui' ? 'success' : 'danger'
                    )
                    ->alignCenter(),
                ImageColumn::make('pic.create_signature')
                    ->label('Pembuat')
                    ->width(150)
                    ->height(75),
                ImageColumn::make('pic.approve_signature')
                    ->label('Penyetuju')
                    ->width(150)
                    ->height(75),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('pdf_view')
                    ->label(_('View PDF'))
                    ->icon('heroicon-o-document')
                    ->color('success')
                    ->url(fn($record) => self::getUrl('pdfJadwalProduksi', ['record' => $record->id])),
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
                fn($query) => $query->where('status_persetujuan', 'Disetujui')
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
