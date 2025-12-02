<?php

namespace App\Filament\Resources\Warehouse\Incomming;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;
use App\Filament\Resources\Warehouse\Incomming\Traits\InformasiMaterial;
use App\Filament\Resources\Warehouse\Incomming\Traits\InformasiUmum;
use App\Filament\Resources\Warehouse\Incomming\Traits\Keterangan;
use App\Models\Warehouse\Incomming\IncommingMaterial;
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

class IncommingMaterialResource extends Resource
{
    use InformasiUmum, InformasiMaterial, Keterangan, HasSignature;
    protected static ?string $model = IncommingMaterial::class;
    protected static ?string $slug = 'warehouse/incoming-material';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Incoming Material';
    protected static ?string $pluralLabel = 'Incoming Material';
    protected static ?string $modelLabel = 'Incoming Material';

    public static function getNavigationBadge(): ?string
    {
        $count = IncommingMaterial::where('status_penerimaan_pic', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penerimaan_pic')
                    ->default('Belum Diterima'),

                self::informasiUmumSection(),

                self::informasiMaterialSection(),

                self::keteranganSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'submited',
                            'role' => 'Diserahkan Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'received',
                            'role' => 'Diterima Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->received_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Warehouse/IncommingMaterial/Signatures'
                ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('permintaanPembelian.permintaanBahanWBB.no_surat')
                    ->label('No Surat Permintaan Bahan'),

                self::textColumn('tanggal', 'Tanggal Penerimaan')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d F Y')),

                TextColumn::make('status_penerimaan_pic')
                    ->label('Status Penerimaan')
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
                        ->visible(fn($record) => $record->status_penerimaan_pic === 'Diterima')
                        ->url(fn($record) => route('pdf.IncomingMaterial', ['record' => $record->id])),
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
            'index' => Pages\ListIncommingMaterials::route('/'),
            'create' => Pages\CreateIncommingMaterial::route('/create'),
            'edit' => Pages\EditIncommingMaterial::route('/{record}/edit'),
            'pdfIncommingMaterial' => Pages\pdfIncommingMaterial::route('/{record}/pdfIncommingMaterial')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'permintaanPembelian',
                'details',
                'pic'
            ]);
    }
}
