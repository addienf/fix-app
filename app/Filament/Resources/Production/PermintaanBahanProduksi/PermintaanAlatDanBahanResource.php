<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;
use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\RelationManagers;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
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
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;


class PermintaanAlatDanBahanResource extends Resource
{
    protected static ?string $model = PermintaanAlatDanBahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Permintaan Bahan dan Alat Produksi';
    protected static ?string $pluralLabel = 'Permintaan Bahan dan Alat Produksi';
    protected static ?string $modelLabel = 'Permintaan Bahan dan Alat Produksi';
    protected static ?string $slug = 'production/permintaan-alat-dan-bahan';

    public static function getNavigationBadge(): ?string
    {
        $count = PermintaanAlatDanBahan::where('status_penyerahan', '!=', 'Diserahkan')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $lastValue = PermintaanAlatDanBahan::latest('no_surat')->value('no_surat');

        return $form
            ->schema([
                //
                Hidden::make('status_penyerahan')
                    ->default('Belum Diserahkan'),

                Hidden::make('status')
                    ->disabledOn('edit')
                    ->default('Belum Diproses'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::selectInput()
                                    ->placeholder('Pilih Nomor SPK')
                                    ->label('No SPK')
                                    ->hiddenOn('edit')
                                    ->columnSpanFull(),

                                self::textInput('no_surat', 'No Surat')
                                    ->unique(ignoreRecord: true)
                                    ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                                    ->hint('Format: XXX/QKS/PRO/PERMINTAAN/MM/YY'),

                                self::datePicker('tanggal', 'Tanggal')
                                    ->required(),

                                self::textInput('dari', 'Dari')
                                    ->placeholder('Produksi')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('kepada', 'Kepada')
                                    ->placeholder('Warehouse')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::buttonGroup('status', 'Status Stock Barang')
                                    ->columnSpanFull()
                                    ->hiddenOn(operations: 'create'),
                            ])
                    ]),
                Section::make('List Detail Bahan Baku')
                    ->collapsible()
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                TableRepeater::make('details')
                                    ->relationship('details')
                                    ->schema([

                                        self::textInput('bahan_baku', 'Bahan Baku'),

                                        self::textInput('spesifikasi', 'Spesifikasi'),

                                        self::textInput('jumlah', 'Jumlah')->numeric(),

                                        Textarea::make('keperluan_barang')
                                            ->required()
                                            ->rows(1)
                                            ->label('Keperluan Barang')

                                    ])
                                    // ->deletable(false)
                                    ->reorderable(false)
                                    // ->addable(false)
                                    ->columnSpanFull()
                            ])
                    ]),

                Section::make('Detail PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Grid::make(1)
                                    ->schema([

                                        self::textInput('dibuat_name', 'Dibuat Oleh'),

                                        self::signatureInput('dibuat_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('diketahui_name', 'Diketahui Oleh'),

                                        self::signatureInput('diketahui_signature', ''),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || filled($record?->diketahui_signature)
                                    ),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('diserahkan_name', 'Diserahkan Kepada'),

                                        self::signatureInput('diserahkan_signature', ''),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || blank($record?->diketahui_signature) || filled($record?->diserahkan_signature)
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
                self::textColumn('spk.no_spk', 'No SPK'),

                self::textColumn('no_surat', 'Nomor Surat'),

                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d M Y'),

                TextColumn::make('status')
                    ->label('Status Stock Barang')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'Tersedia' => 'success',
                            'Tidak Tersedia' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->alignCenter(),

                TextColumn::make('status_penyerahan')
                    ->label('Status Penyerahan')
                    ->badge()
                    ->color(function ($record) {
                        $penyelesaian = $record->status_penyerahan;
                        $persetujuan = $record->status_persetujuan;

                        if ($penyelesaian === 'Diserahkan') {
                            return 'success';
                        }

                        if ($penyelesaian !== 'Diketahui') {
                            return 'danger';
                        }

                        return 'warning';
                    })
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
                        ->visible(fn($record) => $record->status_penyerahan === 'Diserahkan')
                        ->url(fn($record) => route('pdf.permintaanAlatBahan', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanAlatDanBahans::route('/'),
            'create' => Pages\CreatePermintaanAlatDanBahan::route('/create'),
            'edit' => Pages\EditPermintaanAlatDanBahan::route('/{record}/edit'),
            'pdfPermintaanAlatdanBahan' => Pages\pdfPermintaanAlatdanBahan::route('/{record}/pdfPermintaanAlatdanBahan')
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
            Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            ->label('spk')
            ->options(function () {
                return SPKMarketing::whereHas('jadwalProduksi', function ($query) {
                    $query->where('status_persetujuan', 'Disetujui');
                })
                    ->whereDoesntHave('permintaan')
                    ->pluck('no_spk', 'id');
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                // Ambil data SPK dan relasi yang dibutuhkan
                $spk = SPKMarketing::find($state);
                if (!$spk)
                    return;

                $set('dari', $spk->dari);
                $set('kepada', $spk->kepada);
            });
    }

    protected static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                'Tersedia' => 'Tersedia',
                'Tidak Tersedia' => 'Tidak Tersedia',
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Production/PermintaanBahan/Signatures');
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
