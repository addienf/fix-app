<?php

namespace App\Filament\Resources\Purchasing\Permintaan;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;
use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\RelationManagers;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Services\SignatureUploader;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PermintaanPembelianResource extends Resource
{
    protected static ?string $model = PermintaanPembelian::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationGroup = 'Purchasing';
    protected static ?string $navigationLabel = 'Permintaan Pembelian';
    protected static ?string $pluralLabel = 'Permintaan Pembelian';
    protected static ?string $modelLabel = 'Permintaan Pembelian';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                self::selectInput('permintaan_bahan_wbb_id', 'No Surat', 'permintaanBahanWBB', 'no_surat')
                    ->columnSpanFull(),
                Fieldset::make('List Detail Bahan Baku')
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
                                                self::textInput('jumlah', 'Jumlah')->numeric()
                                                    ->extraAttributes([
                                                        'readonly' => true,
                                                        'style' => 'pointer-events: none;'
                                                    ]),
                                                Textarea::make('keterangan')
                                                    ->label('Keterangan')
                                            ])
                                    ])
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->addable(false)
                                    ->columnSpanFull()
                            ])
                    ]),
                Fieldset::make('PIC')
                    ->relationship('pic')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('create_name', 'Dibuat Oleh'),
                                self::textInput('knowing_name', 'Mengetahui'),
                                self::signatureInput('create_signature', ''),
                                self::signatureInput('knowing_signature', ''),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('permintaanBahanWBB.no_surat', 'No Surat WBB'),
                ImageColumn::make('pic.create_signature')
                    ->label('Pembuat')
                    ->width(150)
                    ->height(75),
                ImageColumn::make('pic.knowing_signature')
                    ->label('Pembuat')
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
                    ->url(fn($record) => self::getUrl('pdfPermintaanPembelian', ['record' => $record->id])),
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
            ->relationship($relation, $title)
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
            ->afterStateUpdated(function ($state, $set) use ($fieldName) {
                if (blank($state)) return;
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
