<?php

namespace App\Filament\Resources\Quality\Standarisasi;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;
use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\RelationManagers;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class StandarisasiDrawingResource extends Resource
{
    protected static ?string $model = StandarisasiDrawing::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Standarisasi Gambar Kerja';
    protected static ?string $pluralLabel = 'Standarisasi Gambar Kerja';
    protected static ?string $modelLabel = 'Standarisasi Gambar Kerja';
    protected static ?string $slug = 'quality/standarisasi-gambar-kerja';

    public static function getNavigationBadge(): ?string
    {
        $count = StandarisasiDrawing::where('status_pemeriksaan', '!=', 'Diperiksa')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_pemeriksaan')
                    ->default('Belum Diperiksa'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        Grid::make($isEdit ? 1 : 2)
                            ->schema([

                                self::selectInputSPK()
                                    ->hiddenOn('edit'),

                                self::datePicker('tanggal', 'Tanggal'),

                            ]),
                    ]),

                Section::make('Identitas Gambar Kerja')
                    ->collapsible()
                    ->relationship('identitas')
                    ->schema([

                        self::textInput('judul_gambar', 'Judul Gambar'),

                        self::textInput('no_gambar', 'No Gambar'),

                        self::datePicker('tanggal_pembuatan', 'Tanggal Pembuatan'),

                        self::buttonGroup('revisi', 'Revisi')
                            ->helperText('*Jika Ya Revisi Ke ')
                            ->reactive()
                            ->columnSpanFull(),

                        self::textInput('revisi_ke', 'Revisi Ke')
                            ->hidden(fn(Get $get) => $get('revisi') != 1),

                        self::textInput('nama_pembuat', 'Nama Pembuat'),

                        self::textInput('nama_pemeriksa', 'Nama Pemeriksa'),

                    ]),

                Section::make('Spesifikasi Teknis')
                    ->collapsible()
                    ->schema([

                        self::selectInputOptions('jenis_gambar', 'Jenis Gambar', 'standarisasi.jenis_gambar')
                            ->placeholder('Pilih Jenis Gambar')
                            ->required(),

                        self::selectInputOptions('format_gambar', 'Format Gambar', 'standarisasi.format_gambar')
                            ->placeholder('Pilih Format Gambar')
                            ->required(),

                    ])->columns(2),

                Section::make('Detail')
                    ->relationship('detail')
                    ->collapsible()
                    ->schema([

                        FileUpload::make('lampiran')
                            ->label('Lampiran')
                            ->directory('Quality/StandarisasiDrawing/Files')
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->helperText('*Hanya file gambar (PNG, JPG, JPEG) yang diperbolehkan. Maksimal ukuran 10 MB.')
                            ->multiple()
                            ->image()
                            ->downloadable()
                            ->reorderable()
                            ->maxSize(10240)
                            ->columnSpanFull()
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan atau Koreksi yang Dibutuhkan')
                            ->required(),

                    ]),

                Section::make('Detail PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        self::textInput('create_name', 'Dibuat Oleh'),
                                        self::signatureInput('create_signature', ''),
                                    ])->hiddenOn(operations: 'edit'),
                                Grid::make(1)
                                    ->schema([
                                        self::textInput('check_name', 'Diperiksa Oleh'),
                                        self::signatureInput('check_signature', ''),
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

                self::textColumn('tanggal', 'Tanggal')
                    ->date('d M Y'),

                self::textColumn('jenis_gambar', 'Jenis Gambar')
                    ->formatStateUsing(function (string $state): string {
                        return config('standarisasi.jenis_gambar')[$state] ?? $state;
                    }),

                self::textColumn('format_gambar', 'Format Gambar'),

                TextColumn::make('status_pemeriksaan')
                    ->label('Status Pemeriksaan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Diperiksa' ? 'success' : 'danger'
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
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_pemeriksaan === 'Diperiksa')
                        ->url(fn($record) => route('pdf.StandarisasiDrawing', ['record' => $record->id])),
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
            'index' => Pages\ListStandarisasiDrawings::route('/'),
            'create' => Pages\CreateStandarisasiDrawing::route('/create'),
            'edit' => Pages\EditStandarisasiDrawing::route('/{record}/edit'),
            'pdfStandarisasiDrawing' => Pages\pdfStandarisasiDrawing::route('/{record}/pdfStandarisasiDrawing')
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function selectInputSPK(): Select
    {
        return
            Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            ->relationship(
                'spk',
                'no_spk',
                fn($query) => $query
                    ->whereHas('permintaan.serahTerimaBahan', function ($query) {
                        $query->where('status_penerimaan', 'Diterima');
                    })->whereDoesntHave('standarisasi')
            )
            ->placeholder('Pilin No SPK')
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
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


    protected static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                1 => 'Ya',
                0 => 'Tidak',
            ])
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->default('individual');
    }

    protected static function datePicker(string $fieldName, string $label): DatePicker
    {
        return
            DatePicker::make($fieldName)
            ->label($label)
            ->displayFormat('M d Y')
            ->seconds(false)
            ->required();
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Quality/StandarisasiDrawing/Signatures');
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
