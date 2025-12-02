<?php

namespace App\Filament\Resources\Sales\SPK;

use App\Filament\Resources\Sales\SPK\SPKResource\Pages;
use App\Filament\Resources\Sales\SPK\Traits\DetailProduk;
use App\Filament\Resources\Sales\SPK\Traits\InformasiUmum;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Traits\HasAutoNumber;
use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SPKResource extends Resource
{
    use SimpleFormResource, HasSignature, HasAutoNumber, InformasiUmum, DetailProduk;
    protected static ?string $model = SPKMarketing::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'sales/spk-marketing';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'SPK Marketing';
    protected static ?string $pluralLabel = 'SPK Marketing';
    protected static ?string $modelLabel = 'SPK Marketing';

    public static function getSlug(): string
    {
        return 'spk-marketing';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = SPKMarketing::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('status_penerimaan')
                    ->default('Belum Diterima'),

                self::informasiUmumSection(),

                self::detailProdukSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'create',
                            'role' => 'Yang Membuat',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'receive',
                            'role' => 'Yang Menerima',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->receive_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Sales/SPK/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spesifikasiProduct.urs.no_urs', 'No URS'),

                self::textColumn('no_order', 'No Order'),

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
                        ->tooltip('Edit Data SPK Marketing')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->tooltip('Lihat Dokumen PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->url(fn($record) => route('pdf.SPKMarketing', ['record' => $record->id]))
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima'),
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
            'index' => Pages\ListSPKS::route('/'),
            'create' => Pages\CreateSPK::route('/create'),
            'edit' => Pages\EditSPK::route('/{record}/edit'),
            'pdfSPK' => Pages\pdfView::route('/{record}/pdfSPK')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spesifikasiProduct.urs',
                'pic',
            ]);
    }
}
