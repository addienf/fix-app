<?php

namespace App\Filament\Resources\Warehouse\Peminjaman;

use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\Pages;
use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\RelationManagers;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PeminjamanAlatResource extends Resource
{
    protected static ?string $model = PeminjamanAlat::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Peminjaman Alat';
    protected static ?string $pluralLabel = 'Peminjaman Alat';
    protected static ?string $modelLabel = 'Peminjaman Alat';
    protected static ?string $slug = 'warehouse/peminjaman-alat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Data Peminjaman Alat')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::datePicker('tanggal_pinjam', 'Tanggal Pinjam')
                                    ->required(),
                                self::datePicker('tanggal_kembali', 'Tanggal Kembali')
                                    ->required(),
                                TableRepeater::make('details')
                                    ->relationship('details')
                                    ->label('Barang')
                                    ->schema([
                                        self::textInput('nama_sparepart', 'Nama Sparepart'),
                                        self::textInput('model', 'Model'),
                                        self::textInput('jumlah', 'Jumlah')->numeric(),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(1)
                                    ->addActionLabel('Tambah Barang')
                                    ->columnSpanFull(),
                            ])
                    ]),
                Section::make('Peminjaman')
                    ->relationship('pic')
                    ->schema([
                        Grid::make()
                            ->columns(2) // Membagi dua kolom
                            ->schema([
                                self::textInput('department', 'Departemen')
                                    ->default(auth()->user()->role),
                                self::textInput('nama_peminjam', 'Nama Peminjam')
                                    ->default(auth()->user()->name),
                            ]),
                        self::signatureInput('signature', 'Tanda Tangan')->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tanggal_pinjam', 'Tanggal Pinjam')->date('d M Y'),

                self::textColumn('pic.department', 'Department'),

                self::textColumn('details.nama_sparepart', 'Nama Sparepart'),

                self::textColumn('details.model', 'Model'),

                self::textColumn('details.jumlah', 'Jumlah'),

                self::textColumn('tanggal_kembali', 'Tanggal Kembali')->date('d M Y'),

                self::textColumn('pic.nama_peminjam', 'Nama Peminjam'),
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
                        // ->url(fn($record) => route('pdf.PeminjamanAlat')),
                        ->url(fn($record) => route('pdf.PeminjamanAlat', ['record' => $record->id])),
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
            ->helperText('*Harap Tandatangan di tengah area yang disediakan.')
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
