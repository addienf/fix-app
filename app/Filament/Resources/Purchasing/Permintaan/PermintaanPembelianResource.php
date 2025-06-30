<?php

namespace App\Filament\Resources\Purchasing\Permintaan;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;
use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\RelationManagers;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PermintaanPembelianResource extends Resource
{
    protected static ?string $model = PermintaanPembelian::class;
    protected static ?string $slug = 'purchasing/permintaan-pembelian';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationGroup = 'Purchasing';
    protected static ?string $navigationLabel = 'Permintaan Pembelian';
    protected static ?string $pluralLabel = 'Permintaan Pembelian';
    protected static ?string $modelLabel = 'Permintaan Pembelian';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Hidden::make('status_persetujuan')
                    ->default('Belum Disetujui'),

                Section::make('Nomor Surat')
                    ->hiddenOn('edit')
                    ->collapsible()
                    ->schema([

                        self::selectInput('permintaan_bahan_wbb_id', 'Pilih Nomor Surat', 'permintaanBahanWBB', 'no_surat')
                            ->placeholder('Pilin No Surat Dari Permintaan Bahan Pembelian')
                            ->columnSpanFull(),

                    ]),

                Section::make('List Detail Bahan Baku')
                    ->collapsible()
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Repeater::make('details')
                                    ->relationship('details')
                                    ->schema([

                                        Grid::make(4)
                                            ->schema([

                                                self::textInput('kode_barang', 'Kode Barang'),

                                                self::textInput('nama_barang', 'Nama Barang')
                                                    ->extraAttributes([
                                                        'readonly' => true,
                                                        'style' => 'pointer-events: none;'
                                                    ]),

                                                // self::selectJenis(),

                                                self::textInput('jumlah', 'Jumlah')->numeric()
                                                    ->extraAttributes([
                                                        'readonly' => true,
                                                        'style' => 'pointer-events: none;'
                                                    ]),

                                                Textarea::make('keterangan')
                                                    ->required()
                                                    ->rows(1)
                                                    ->label('Keterangan')

                                            ])

                                    ])
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

                                Grid::make(1)
                                    ->schema([
                                        self::textInput('create_name', 'Dibuat Oleh'),
                                        // ->placeholder('Marketing'),
                                        self::signatureInput('create_signature', ''),
                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([
                                        self::textInput('knowing_name', 'Mengetahui'),
                                        // ->placeholder('Produksi'),
                                        self::signatureInput('knowing_signature', ''),
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
                self::textColumn('permintaanBahanWBB.no_surat', 'No Surat WBB'),

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
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('pdf_view')
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_persetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.PermintaanPembelian', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanPembelians::route('/'),
            'create' => Pages\CreatePermintaanPembelian::route('/create'),
            'edit' => Pages\EditPermintaanPembelian::route('/{record}/edit'),
            'pdfPermintaanPembelian' => Pages\pdfPermintaanPembelian::route('/{record}/pdfPermintaanPembelian')
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
            ->relationship($relation, $title, fn($query) => $query->whereDoesntHave('pembelian'))
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $pab = PermintaanBahan::with('details')->find($state);

                if (!$pab)
                    return;

                $detailBahan = $pab->details?->map(function ($detail) {
                    return [
                        'nama_barang' => $detail->bahan_baku ?? '',
                        // 'spesifikasi' => $detail->spesifikasi ?? '',
                        'jumlah' => $detail->jumlah ?? 0,
                        // 'keperluan_barang' => $detail->keperluan_barang ?? '',
                    ];
                })->toArray();

                $set('details', $detailBahan);
            })
        ;
    }

    // protected static function selectJenis(): Select
    // {
    //     return
    //         Select::make('jenis_barang')
    //         ->label('Jenis Barang')
    //         ->required()
    //         ->placeholder('Pilih Jenis Barang')
    //         ->options([
    //             'ss' => 'Produk SS',
    //             'non_ss' => 'Produk Non SS',
    //         ]);
    // }

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
                $path = SignatureUploader::handle($state, 'ttd_', 'Purchasing/PermintaanPembelian/Signatures');
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
