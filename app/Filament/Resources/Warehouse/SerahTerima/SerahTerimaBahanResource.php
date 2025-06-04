<?php

namespace App\Filament\Resources\Warehouse\SerahTerima;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;
use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\RelationManagers;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
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

class SerahTerimaBahanResource extends Resource
{
    protected static ?string $model = SerahTerimaBahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-2-stack';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Serah Terima Bahan';
    protected static ?string $pluralLabel = 'Serah Terima Bahan';
    protected static ?string $modelLabel = 'Serah Terima Bahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                self::selectInput('permintaan_bahan_pro_id', 'No Surat', 'permintaanBahanPro', 'no_surat')
                    ->columnSpanFull(),
                Fieldset::make('Informasi Umum')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('no_surat', 'No Surat'),
                                self::datePicker('tanggal', 'Tanggal'),
                                self::textInput('dari', 'Dari'),
                                self::textInput('kepada', 'Kepada'),
                            ])
                    ]),
                Fieldset::make('List Detail Bahan Baku')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Repeater::make('details')
                                    ->relationship('details')
                                    ->schema([
                                        Grid::make(4)
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
                                                    ->extraAttributes([
                                                        'readonly' => true,
                                                        'style' => 'pointer-events: none;'
                                                    ])
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
                                self::textInput('submit_name', 'Diserahkan Oleh'),
                                self::textInput('receive_name', 'Diterima Oleh'),
                                self::signatureInput('submit_signature', ''),
                                self::signatureInput('receive_signature', ''),
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
                self::textColumn('no_surat', 'Nomor Surat Serah Terima Bahan'),
                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d M Y'),
                self::textColumn('dari', 'Dari'),
                self::textColumn('kepada', 'Kepada'),
                ImageColumn::make('pic.submit_signature')
                    ->label('Pembuat')
                    ->width(150)
                    ->height(75),
                ImageColumn::make('pic.receive_signature')
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
                    ->url(fn($record) => self::getUrl('pdfSerahTerimaBahan', ['record' => $record->id])),
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
                fn($query) => $query->where('status', true)
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
