<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical;

use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\Pages;
use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\Traits\InformasiProduk;
use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\Traits\PengecekanDanPenerimaan;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;

class PenyerahanElectricalResource extends Resource
{
    use InformasiProduk, PengecekanDanPenerimaan, HasSignature;
    protected static ?string $model = PenyerahanElectrical::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 12;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Serah Terima Electrical';
    protected static ?string $pluralLabel = 'Serah Terima Electrical';
    protected static ?string $modelLabel = 'Serah Terima Electrical';
    protected static ?string $slug = 'produksi/serah-terima-electrical';

    public static function getNavigationBadge(): ?string
    {
        $count = PenyerahanElectrical::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                self::getInformasiProdukSection(),

                self::getPengecekanDanPenerimaanSection(),

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
                        [
                            'prefix' => 'knowing',
                            'role' => 'Diketahui Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->receive_signature) || filled($record?->knowing_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Sales/Spesifikasi/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('nama_produk', 'Nama Produk'),

                self::textColumn('no_spk', 'Nomor SPK Marketing'),

                self::textColumn('no_seri', 'No Seri')
                    ->getStateUsing(function ($record) {
                        return $record?->pengecekanSS?->kelengkapanMaterial?->standarisasiDrawing?->serahTerimaWarehouse
                            ?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi?->jadwalProduksi
                            ?->identifikasiProduks?->pluck('no_seri')->filter()->implode(', ') ?? '-';
                    }),

                self::textColumn('status_penyelesaian', 'Status')
                    ->badge()
                    ->color(fn($state) => [
                        'Belum Diterima' => 'danger',
                        'Diterima' => 'warning',
                        'Disetujui' => 'success',
                    ][$state] ?? 'gray')
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
                        ->visible(fn($record) => $record->status_penyelesaian === 'Disetujui')
                        ->url(fn($record) => route('pdf.penyerahanElectrical', ['record' => $record->id])),
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
            'index' => Pages\ListPenyerahanElectricals::route('/'),
            'create' => Pages\CreatePenyerahanElectrical::route('/create'),
            'edit' => Pages\EditPenyerahanElectrical::route('/{record}/edit'),
            'pdfPenyerahanElectrical' => Pages\pdfPenyerahanElectrical::route('/{record}/pdfPenyerahanElectrical')
        ];
    }
}
