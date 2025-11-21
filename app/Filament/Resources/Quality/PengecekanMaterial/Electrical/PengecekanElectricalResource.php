<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages\pdfPengecekanElectrical;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\Traits\ChamberIdentification;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\Traits\TabelKelengkapanMaterial;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengecekanElectricalResource extends Resource
{
    use ChamberIdentification, TabelKelengkapanMaterial, HasSignature;
    protected static ?string $model = PengecekanMaterialElectrical::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 17;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Pengecekan Material Electrical';
    protected static ?string $pluralLabel = 'Pengecekan Material Electrical';
    protected static ?string $modelLabel = 'Pengecekan Material Electrical';
    protected static ?string $slug = 'quality/pengecekan-material-electrical';
    public static function getNavigationBadge(): ?string
    {
        $count = PengecekanMaterialElectrical::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                self::getChamberIdentificationSection($form),

                self::getTabelKelengkapanMaterialSection(),

                self::getNote(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'inspected',
                            'role' => 'Inspected by',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'accepted',
                            'role' => 'Accepted by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->accepted_signature)
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Approved by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/PengecekanMaterial/Electrical/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk.no_spk')
                    ->label('No SPK Marketing'),

                TextColumn::make('penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks.no_seri')
                    ->label('No Seri'),

                self::textColumn('tipe', 'Type/Model'),

                self::textColumn('volume', 'Volume'),

                TextColumn::make('status_penyelesaian')
                    ->label('Status Penyelesaian')
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
                        ->url(fn($record) => route('pdf.pengecekanElectrical', ['record' => $record->id])),
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
            'index' => Pages\ListPengecekanElectricals::route('/'),
            'create' => Pages\CreatePengecekanElectrical::route('/create'),
            'edit' => Pages\EditPengecekanElectrical::route('/{record}/edit'),
            'pdfPengecekanElectrical' => pdfPengecekanElectrical::route('/{record}/pdfPengecekanElectrical')
        ];
    }
}
