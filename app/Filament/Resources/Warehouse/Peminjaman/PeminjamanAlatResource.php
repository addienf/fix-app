<?php

namespace App\Filament\Resources\Warehouse\Peminjaman;

use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\Pages;
use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\RelationManagers;
use App\Filament\Resources\Warehouse\Peminjaman\Traits\DataPeminjamAlat;
use App\Filament\Resources\Warehouse\Peminjaman\Traits\Peminjaman;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Services\SignatureUploader;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
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
use Illuminate\Support\Str;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PeminjamanAlatResource extends Resource
{
    use DataPeminjamAlat, Peminjaman, HasSignature;
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

                self::dataPeminjamanSection(),

                self::peminjamanSection(),
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
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data Spesifikasi')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'details',
                'pic'
            ]);
    }
}
