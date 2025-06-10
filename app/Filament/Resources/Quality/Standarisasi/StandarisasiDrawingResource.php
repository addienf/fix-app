<?php

namespace App\Filament\Resources\Quality\Standarisasi;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;
use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\RelationManagers;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
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

class StandarisasiDrawingResource extends Resource
{
    protected static ?string $model = StandarisasiDrawing::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Standarisasi Drawing';
    protected static ?string $pluralLabel = 'Standarisasi Drawing';
    protected static ?string $modelLabel = 'Standarisasi Drawing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::selectInput('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                                    ->required(),
                                self::datePicker('tanggal', 'Tanggal')
                                    ->required(),
                            ]),
                    ]),
                Section::make('Identitas Gambar Kerja')
                    ->collapsible()
                    ->relationship('identitas')
                    ->schema([
                        self::textInput('judul_gambar', 'Judul Gambar')
                            ->required(),
                        self::textInput('no_gambar', 'No Gambar')
                            ->required(),
                        self::datePicker('tanggal_pembuatan', 'Tanggal Pembuatan')
                            ->required(),
                        ToggleButtons::make('revisi')
                            ->boolean()
                            ->grouped()
                            ->inline(false)
                            ->required(),
                        self::textInput('nama_pembuat', 'Nama Pembuat')
                            ->required(),
                        self::textInput('nama_pemeriksa', 'Nama Pemeriksa')
                            ->required(),
                    ]),
                Section::make('Spesifikasi Teknis')
                    ->collapsible()
                    ->schema([
                        self::selectInputOptions('jenis_gambar', 'Jenis Gambar', 'standarisasi.jenis_gambar')
                            ->required(),
                        self::selectInputOptions('format_gambar', 'Format Gambar', 'standarisasi.format_gambar')
                            ->required(),
                    ])->columns(2),
                Section::make('Detail')
                    ->relationship('detail')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('lampiran')
                            ->label('Lampiran')
                            ->directory('Quality/StandarisasiDrawing/Files')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->columnSpanFull()
                            ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.')
                            ->required(),
                        Textarea::make('catatan')
                            ->label('Catatan atau Koreksi yang Dibutuhkan')
                            ->required(),
                    ]),
                Section::make('PIC')
                    ->relationship('pic')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('create_name', 'Dibuat Oleh')
                                    ->required(),
                                self::textInput('check_name', 'Diperiksa Oleh')
                                    ->required(),
                                self::signatureInput('create_signature', '')
                                    ->required(),
                                self::signatureInput('check_signature', '')
                                    ->required(),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'NO SPK'),
                self::textColumn('tanggal', 'Tanggal')
                    ->date('d - M - Y'),
                self::textColumn('jenis_gambar', 'Jenis Gambar')
                    ->formatStateUsing(function (string $state): string {
                        return config('standarisasi.jenis_gambar')[$state] ?? $state;
                    }),
                self::textColumn('format_gambar', 'Format Gambar'),
                ImageColumn::make('pic.create_signature')
                    ->width(150)
                    ->label('PIC')
                    ->height(75),
                ImageColumn::make('pic.check_signature')
                    ->width(150)
                    ->label('PIC')
                    ->height(75),
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
                        ->url(fn($record) => self::getUrl('pdfStandarisasiDrawing', ['record' => $record->id])),
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