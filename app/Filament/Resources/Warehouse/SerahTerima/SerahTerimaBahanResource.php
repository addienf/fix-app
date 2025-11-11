<?php

namespace App\Filament\Resources\Warehouse\SerahTerima;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;
use App\Filament\Resources\Warehouse\SerahTerima\Traits\DetailBahanBaku;
use App\Filament\Resources\Warehouse\SerahTerima\Traits\InformasiUmum;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Traits\HasSignature;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SerahTerimaBahanResource extends Resource
{
    use InformasiUmum, DetailBahanBaku, HasSignature;
    protected static ?string $model = SerahTerimaBahan::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Serah Terima Bahan';
    protected static ?string $pluralLabel = 'Serah Terima Bahan';
    protected static ?string $modelLabel = 'Serah Terima Bahan';
    protected static ?string $slug = 'warehouse/serah-terima-bahan';

    public static function getNavigationBadge(): ?string
    {
        $count = SerahTerimaBahan::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penerimaan')
                    ->default('Belum Diterima'),

                self::informasiUmumSection(),

                self::detailBahanSection(),

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
                    uploadPath: 'Warehouse/SerahTerimaBahan/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('permintaanBahanPro.no_surat', 'No Surat Production'),

                self::textColumn('no_surat', 'Nomor Surat Serah Terima Bahan'),

                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d M Y'),

                TextColumn::make('status_penerimaan')
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
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima')
                        ->url(fn($record) => route('pdf.serahTerima', ['record' => $record->id])),
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
            'index' => Pages\ListSerahTerimaBahans::route('/'),
            'create' => Pages\CreateSerahTerimaBahan::route('/create'),
            'edit' => Pages\EditSerahTerimaBahan::route('/{record}/edit'),
            'pdfSerahTerimaBahan' => Pages\pdfSerahTerimaBahan::route('/{record}/pdfSerahTerimaBahan')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'permintaanBahanPro',
                'details',
                'pic'
            ]);
    }
}
