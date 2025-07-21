<?php

namespace App\Filament\Resources\Warehouse\SerahTerima;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;
use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\RelationManagers;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Services\SignatureUploader;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

class SerahTerimaBahanResource extends Resource
{
    protected static ?string $model = SerahTerimaBahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Serah Terima Bahan';
    protected static ?string $pluralLabel = 'Serah Terima Bahan';
    protected static ?string $modelLabel = 'Serah Terima Bahan';
    protected static ?string $slug = 'warehouse/serah-terima-bahan';

    public static function getNavigationBadge(): ?string
    {
        $count = SerahTerimaBahan::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }


    public static function form(Form $form): Form
    {
        $lastValue = SerahTerimaBahan::latest('no_surat')->value('no_surat');

        return $form
            ->schema([
                //
                Hidden::make('status_penerimaan')
                    ->default('Belum Diterima'),

                Section::make('Nomor Surat')
                    ->hiddenOn('edit')
                    ->collapsible()
                    ->schema([

                        self::selectInput('permintaan_bahan_pro_id', 'Pilih Nomor Surat', 'permintaanBahanPro', 'no_surat')
                            ->placeholder('Pilin No Surat Dari Permintaan Alat dan Bahan Produksi')
                            ->columnSpanFull(),

                    ]),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                self::textInput('no_surat', 'No Surat')
                                    ->unique(ignoreRecord: true)
                                    ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                                    ->hint('Format: XXX/QKS/WBB/SERAHTERIMA/MM/YY'),

                                self::datePicker('tanggal', 'Tanggal')
                                    ->required(),

                                self::textInput('dari', 'Dari')
                                    ->placeholder('Warehouse'),

                                self::textInput('kepada', 'Kepada'),
                            ])
                    ]),

                Section::make('List Detail Bahan Baku')
                    ->collapsible()
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                TableRepeater::make('details')
                                    ->label('')
                                    ->relationship('details')
                                    ->schema([

                                        self::textInput('bahan_baku', 'Bahan Baku')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),
                                        self::textInput('spesifikasi', 'Spesifikasi')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),
                                        self::textInput('jumlah', 'Jumlah')->numeric()
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),
                                        Textarea::make('keperluan_barang')
                                            ->label('Keperluan Barang')
                                            ->rows(1)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ])

                                    ])
                                    ->columns(4)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->addable(false)
                                    ->columnSpanFull()

                            ])

                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                // Grid::make(1)
                                //     ->schema([

                                //         self::textInput('submit_name', 'Diserahkan oleh'),

                                //         self::signatureInput('submit_signature', ''),

                                //     ])->hiddenOn(operations: 'edit'),

                                // Grid::make(1)
                                //     ->schema([

                                //         self::textInput('receive_name', 'Diterima oleh'),

                                //         self::signatureInput('receive_signature', ''),

                                //     ])->hiddenOn(operations: 'create'),

                                Grid::make(1)
                                    ->schema([
                                        Hidden::make('submit_name')
                                            ->default(fn() => auth()->id()),

                                        self::textInput('submit_name_placeholder', 'Diserahkan oleh')
                                            ->default(fn() => auth()->user()?->name)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::signatureInput('submit_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([
                                        Hidden::make('receive_name')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        self::textInput('receive_name_placeholder', 'Diterima oleh')
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->required(false)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        self::signatureInput('receive_signature', ''),

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
                self::textColumn('permintaanBahanPro.no_surat', 'No Surat Production'),

                self::textColumn('no_surat', 'Nomor Surat Serah Terima Bahan'),

                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d M Y'),

                TextColumn::make('status_penerimaan')
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
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima')
                        ->url(fn($record) => route('pdf.serahTerima', ['record' => $record->id])),
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
            'index' => Pages\ListSerahTerimaBahans::route('/'),
            'create' => Pages\CreateSerahTerimaBahan::route('/create'),
            'edit' => Pages\EditSerahTerimaBahan::route('/{record}/edit'),
            'pdfSerahTerimaBahan' => Pages\pdfSerahTerimaBahan::route('/{record}/pdfSerahTerimaBahan')
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
            // ->relationship($relation, $title)
            ->relationship(
                $relation,
                $title,
                fn($query) => $query
                    ->where('status_penyerahan', 'Diserahkan')
                    ->where('status', 'Tersedia')
                    ->whereDoesntHave('serahTerimaBahan')
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

                $pab = PermintaanAlatDanBahan::with('details')->find($state);

                if (!$pab)
                    return;

                $detailBahan = $pab->details?->map(function ($detail) {
                    return [
                        'bahan_baku' => $detail->bahan_baku ?? '',
                        'spesifikasi' => $detail->spesifikasi ?? '',
                        'jumlah' => $detail->jumlah ?? 0,
                        'keperluan_barang' => $detail->keperluan_barang ?? '',
                    ];
                })->toArray();

                $set('details', $detailBahan);
            })
        ;
    }

    protected static function datePicker(string $fieldName, string $label): DatePicker
    {
        return
            DatePicker::make($fieldName)
            ->label($label)
            ->required()
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Warehouse/SerahTerimaBahan/Signatures');
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
