<?php

namespace App\Filament\Resources\Purchasing\Penerimaan;

use App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Pages;
use App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\RelationManagers;
use App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Traits\NoSurat;
use App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Traits\TabelBarang;
use App\Models\Purchasing\Penerimaan\PenerimaanBarang;
use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenerimaanBarangResource extends Resource
{
    use NoSurat, SimpleFormResource, HasSignature, TabelBarang;
    protected static ?string $model = PenerimaanBarang::class;
    protected static ?string $slug = 'purchasing/penerimaan-barang';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'Purchasing';
    protected static ?string $navigationLabel = 'Penerimaan Barang';
    protected static ?string $pluralLabel = 'Penerimaan Barang';
    protected static ?string $modelLabel = 'Penerimaan Barang';

    public static function getNavigationBadge(): ?string
    {
        $count = PenerimaanBarang::where('status', '!=', 'Diketahui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Belum Diketahui'),

                self::infoSection(),

                self::informasiMaterial(),

                self::pemeriksaanBarang(),

                self::getSignature()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tanggal_penerimaan', 'Tanggal Penerimaan')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d F Y')),
                self::textColumn('details.nama_material', 'Nama Material')
                    ->getStateUsing(function ($record) {
                        return $record->details->first()?->nama_material ?? '-';
                    }),
                self::textColumn('nomor_po', 'Nomor PO'),
                self::textColumn('status', 'Status')
                    ->badge()
                    ->searchable(false)
                    ->sortable(false)
                    ->color(fn(?string $state): string => match ($state) {
                        'Diketahui' => 'success',
                        'Belum Ditanda Tangan Penerima' => 'warning',
                        default => 'danger',
                    })
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data SPK Marketing')
                        ->color('info'),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->tooltip('Lihat Dokumen PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->url(fn($record) => route('pdf.PenerimaanBarang', ['record' => $record->id])),
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
            'index' => Pages\ListPenerimaanBarangs::route('/'),
            'create' => Pages\CreatePenerimaanBarang::route('/create'),
            'edit' => Pages\EditPenerimaanBarang::route('/{record}/edit'),
        ];
    }
}
