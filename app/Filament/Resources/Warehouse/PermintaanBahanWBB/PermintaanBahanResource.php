<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;
use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\RelationManagers;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Services\SignatureUploader;
use Filament\Forms\Components\Section;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
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

class PermintaanBahanResource extends Resource
{
    protected static ?string $model = PermintaanBahan::class;
    protected static ?string $slug = 'Warehouse/permintaan-pembelian-bahan-warehpuse';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Permintaan Bahan Warehouse';
    protected static ?string $pluralLabel = 'Permintaan Bahan Warehouse';
    protected static ?string $modelLabel = 'Permintaan Bahan Warehouse';

    public static function form(Form $form): Form
    {
        $lastValue = PermintaanBahan::latest('no_surat')->value('no_surat');

        return $form
            ->schema([
                //
                Section::make('Nomor Surat')
                    ->collapsible()
                    ->schema([

                        self::selectInput('permintaan_bahan_pro_id', 'permintaanBahanPro', 'no_surat', 'Pilih Nomor Surat')
                            ->placeholder('Pilin No Surat Dari Permintaan Alat dan Bahan Produksi')
                            ->columnSpanFull(),

                    ])
                    ->hiddenOn('edit'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                self::textInput('no_surat', 'No Surat')
                                    ->unique(ignoreRecord: true)
                                    ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                                    ->hint('Format: XXX/QKS/WBB/PERMINTAAN/MM/YY'),

                                self::datePicker('tanggal', 'Tanggal')
                                    ->required(),

                                self::textInput('dari', 'Dari')
                                    ->placeholder('Warehouse'),

                                self::textInput('kepada', 'Kepada')
                                    ->placeholder('Purchasing'),

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
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('permintaanBahanPro.no_surat', 'No Surat Production'),
                self::textColumn('no_surat', 'Nomor Surat Warehouse'),
                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d M Y'),
                self::textColumn('dari', 'Dari'),
                self::textColumn('kepada', 'Kepada'),
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
                        ->url(fn($record) => route('pdf.permintaanBahan', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanBahans::route('/'),
            'create' => Pages\CreatePermintaanBahan::route('/create'),
            'edit' => Pages\EditPermintaanBahan::route('/{record}/edit'),
            'pdfPermintaanBahanWBB' => Pages\pdfPermintaanBahanWBB::route('/{record}/pdfPermintaanBahanWBB')
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function selectInput($fieldName, $relation, $title, $label): Select
    {
        return
            Select::make($fieldName)
            // ->relationship($relation, $title)
            ->relationship(
                $relation,
                $title,
                fn($query) => $query
                    ->where('status_penyerahan', 'Diserahkan')
                    ->where('status', 'Tidak Tersedia')
                    ->whereDoesntHave('permintaanBahanWBB')
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Warehouse/PermintaanBahan/Signatures');
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
