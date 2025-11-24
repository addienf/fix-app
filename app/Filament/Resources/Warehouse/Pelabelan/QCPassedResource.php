<?php

namespace App\Filament\Resources\Warehouse\Pelabelan;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;
use App\Filament\Resources\Warehouse\Pelabelan\Traits\DetailLaporanProduk;
use App\Filament\Resources\Warehouse\Pelabelan\Traits\InformasiUmum;
use App\Filament\Resources\Warehouse\Pelabelan\Traits\SyaratDanKetentuan;
use App\Models\Warehouse\Pelabelan\QCPassed;
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

class QCPassedResource extends Resource
{
    use InformasiUmum, DetailLaporanProduk, SyaratDanKetentuan, HasSignature;
    protected static ?string $model = QCPassed::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Pelabelan QC Passed';
    protected static ?string $pluralLabel = 'Pelabelan QC Passed';
    protected static ?string $modelLabel = 'Pelabelan QC Passed';
    protected static ?string $slug = 'warehouse/pelabelan-qc-passed';

    public static function getNavigationBadge(): ?string
    {
        $count = QCPassed::where('status_persetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_persetujuan')
                    ->default('Belum Disetujui'),

                self::getInformasiUmumSection($form),

                self::getDetailLaporanProdukSection(),

                self::getSyaratDanKetentuanSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'created',
                            'role' => 'Dibuat Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Disetujui Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/PengecekanMaterial/SS/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('productRelease.pengecekanPerforma.penyerahanProdukJadi.details.no_spk', 'Nomor SPK'),

                self::textColumn('productRelease.pengecekanPerforma.serial_number', 'Serial Number'),

                self::textColumn('penanggung_jawab', 'Penanggung Jawab'),

                TextColumn::make('status_persetujuan')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Disetujui' ? 'success' : 'danger'
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
                        ->tooltip('Edit Data SPK Marketing')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_persetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.PelabelanQCPassed', ['record' => $record->id])),
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
            'index' => Pages\ListQCPasseds::route('/'),
            'create' => Pages\CreateQCPassed::route('/create'),
            'edit' => Pages\EditQCPassed::route('/{record}/edit'),
            'pdfPelabelanQCPassed' => Pages\pdfPelabelanQCPassed::route('/{record}/pdfPelabelanQCPassed')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'productRelease.pengecekanPerforma.penyerahanProdukJadi.details'
            ]);
    }
}
