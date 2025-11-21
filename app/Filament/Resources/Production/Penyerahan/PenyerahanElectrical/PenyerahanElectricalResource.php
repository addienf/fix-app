<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical;

use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\Pages;
use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\RelationManagers;
use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\Traits\InformasiProduk;
use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\Traits\PengecekanDanPenerimaan;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

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

                TextColumn::make('pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks.no_seri')
                    ->label('No Seri'),

                TextColumn::make('status_penyelesaian')
                    ->label('Status')
                    ->badge()
                    ->color(function ($record) {
                        $penyelesaian = $record->status_penyelesaian;
                        $persetujuan = $record->status_persetujuan;

                        if ($penyelesaian === 'Disetujui') {
                            return 'success';
                        }

                        if ($penyelesaian !== 'Diterima' && $persetujuan !== 'Disetujui') {
                            return 'danger';
                        }

                        return 'warning';
                    })
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
