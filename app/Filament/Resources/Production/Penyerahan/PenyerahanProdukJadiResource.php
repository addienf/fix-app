<?php

namespace App\Filament\Resources\Production\Penyerahan;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;
use App\Filament\Resources\Production\Penyerahan\Traits\DetailJadwalProduksi;
use App\Filament\Resources\Production\Penyerahan\Traits\InformasiUmum;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PenyerahanProdukJadiResource extends Resource
{
    use InformasiUmum, DetailJadwalProduksi, HasSignature;
    protected static ?string $model = PenyerahanProdukJadi::class;
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Penyerahan Produk Jadi';
    protected static ?string $pluralLabel = 'Penyerahan Produk Jadi';
    protected static ?string $modelLabel = 'Penyerahan Produk Jadi';
    protected static ?string $slug = 'produksi/penyerahan-produk-jadi';

    public static function getNavigationBadge(): ?string
    {
        $count = PenyerahanProdukJadi::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        // $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penerimaan')
                    ->default('Belum Diterima'),

                self::getInformasiUmumSection($form),

                self::getDetailJadwalProduksiSection(),

                self::getKondisiProduk(),

                self::getCatatanPenting(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'submit',
                            'role' => 'Diserahkan Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'receive',
                            'role' => 'Diterima Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->receive_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Production/PenyerahanProdukJadi/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk.no_spk', 'No SPK Marketing'),

                self::textColumn('no_seri', 'No Seri')
                    ->getStateUsing(function ($record) {
                        return $record?->pengecekanElectrical?->penyerahanElectrical?->pengecekanSS
                            ?->kelengkapanMaterial?->standarisasiDrawing?->serahTerimaWarehouse
                            ?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi?->jadwalProduksi
                            ?->identifikasiProduks?->pluck('no_seri')->filter()->implode(', ') ?? '-';
                    }),

                self::textColumn('penanggug_jawab', 'Penanggung Jawab'),

                self::textColumn('status_penerimaan', 'Status')
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
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima')
                        ->url(fn($record) => route('pdf.PenyerahanProdukJadi', ['record' => $record->id])),
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
            'index' => Pages\ListPenyerahanProdukJadis::route('/'),
            'create' => Pages\CreatePenyerahanProdukJadi::route('/create'),
            'edit' => Pages\EditPenyerahanProdukJadi::route('/{record}/edit'),
            'pdfPenyerahanProdukJadi' => Pages\pdfPenyerahanProdukJadi::route('/{record}/pdfPenyerahanProdukJadi')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                'pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            ]);
    }
}
