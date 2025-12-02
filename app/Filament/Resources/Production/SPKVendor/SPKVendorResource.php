<?php

namespace App\Filament\Resources\Production\SPKVendor;

use App\Filament\Resources\Production\SPKVendor\SPKVendorResource\Pages;
use App\Filament\Resources\Production\SPKVendor\Traits\DokumenPendukung;
use App\Filament\Resources\Production\SPKVendor\Traits\InformasiUmum;
use App\Filament\Resources\Production\SPKVendor\Traits\ListDetailBahanBaku;
use App\Models\Production\SPK\SPKVendor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SPKVendorResource extends Resource
{
    use InformasiUmum, ListDetailBahanBaku, DokumenPendukung;
    protected static ?string $model = SPKVendor::class;
    protected static ?int $navigationSort = 13;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'SPK Vendor';
    protected static ?string $pluralLabel = 'SPK Vendor';
    protected static ?string $modelLabel = 'SPK Vendor';
    protected static ?string $slug = 'production/spk-vendor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //

                self::informasiUmumSection(),

                self::ListDetailBahanBakuSection(),

                self::dokumenPendukungSection()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('no_spk_vendor', 'No SPK Vendor'),

                self::textColumn('permintaanBahanProduksi.jadwalProduksi.spk.no_spk', 'No SPK Marketing'),

                self::textColumn('no_seri', 'No Seri Product')
                    ->getStateUsing(function ($record) {
                        return $record
                            ->permintaanBahanProduksi
                            ?->jadwalProduksi
                            ?->identifikasiProduks
                            ?->pluck('no_seri')
                            ->implode(', ') ?? '-';
                    }),

                self::textColumn('nama_perusahaan', 'Nama Perusahaan')
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data SPK Marketing')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->url(fn($record) => route('pdf.spkVendor', ['record' => $record->id])),
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
            'index' => Pages\ListSPKVendors::route('/'),
            'create' => Pages\CreateSPKVendor::route('/create'),
            'edit' => Pages\EditSPKVendor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'permintaanBahanProduksi.jadwalProduksi.spk',
                'permintaanBahanProduksi.jadwalProduksi.identifikasiProduks'
            ]);
    }
}
