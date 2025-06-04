<?php

namespace App\Filament\Resources\Warehouse\Peminjaman;

use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\Pages;
use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\RelationManagers;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PeminjamanAlatResource extends Resource
{
    protected static ?string $model = PeminjamanAlat::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Peminjaman Alat';
    protected static ?string $pluralLabel = 'Peminjaman Alat';
    protected static ?string $modelLabel = 'Peminjaman Alat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Fieldset::make('')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::datePicker('tanggal_pinjam', 'Tanggal Pinjam'),
                                self::datePicker('tanggal_kembali', 'Tanggal Kembali'),
                                Repeater::make('details')
                                    ->relationship('details')
                                    ->label('Barang')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                self::textInput('nama_sparepart', 'Nama Sparepart'),
                                                self::textInput('model', 'Model'),
                                                self::textInput('jumlah', 'Jumlah')->numeric(),
                                            ])
                                    ])
                                    ->defaultItems(1)
                                    ->columnSpanFull(),
                            ])
                    ]),
                Fieldset::make('')
                    ->relationship('pic')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                self::textInput('department', 'Department')
                                    ->default(auth()->user()->role),
                                self::textInput('nama_peminjam', 'Nama Peminjam')
                                    ->default(auth()->user()->name),
                                self::signatureInput('signature', 'Tanda Tangan')
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tanggal_pinjam', 'Tanggal Pinjam')->date('d/m/Y'),
                self::textColumn('pic.department', 'Department'),
                self::textColumn('details.nama_sparepart', 'Nama Sparepart'),
                self::textColumn('details.model', 'Model'),
                self::textColumn('details.jumlah', 'Jumlah'),
                self::textColumn('tanggal_kembali', 'Tanggal Kembali')->date('d/m/Y'),
                self::textColumn('pic.nama_peminjam', 'Nama Peminjam'),
                ImageColumn::make('pic.signature')
                    ->label('Tanda Tangan')
                    ->width(75)
                    ->height(37.5),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPeminjamanAlats::route('/'),
            'create' => Pages\CreatePeminjamanAlat::route('/create'),
            'edit' => Pages\EditPeminjamanAlat::route('/{record}/edit'),
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Warehouse/PeminjamanAlat/Signatures');
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
