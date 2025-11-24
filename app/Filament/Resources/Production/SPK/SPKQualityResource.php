<?php

namespace App\Filament\Resources\Production\SPK;

use App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;
use App\Filament\Resources\Production\SPK\Traits\DetailProduk;
use App\Filament\Resources\Production\SPK\Traits\InformasiUmum;
use App\Models\Production\SPK\SPKQuality;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SPKQualityResource extends Resource
{
    use InformasiUmum, DetailProduk, HasSignature;
    protected static ?string $model = SPKQuality::class;
    protected static ?int $navigationSort = 13;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'SPK Quality';
    protected static ?string $pluralLabel = 'SPK Quality';
    protected static ?string $modelLabel = 'SPK Quality';
    protected static ?string $slug = 'produksi/spk-quality';


    public static function getNavigationBadge(): ?string
    {
        $count = SPKQuality::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penerimaan')
                    ->default('Belum Diterima'),

                self::getInformasiUmumSection($form),

                self::getDetailProdukSection(),

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
                    uploadPath: 'Production/SPKQuality/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('no_spk', 'No SPK QUality'),

                self::textColumn('dari', 'Dari'),

                self::textColumn('kepada', 'Kepada'),

                self::textColumn('status_penerimaan', 'Status Penerimaan')
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
                        ->url(fn($record) => route('pdf.spkQuality', ['record' => $record->id])),
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
            'index' => Pages\ListSPKQualities::route('/'),
            'create' => Pages\CreateSPKQuality::route('/create'),
            'edit' => Pages\EditSPKQuality::route('/{record}/edit'),
            'pdfSPKQuality' => Pages\pdfSPKQuality::route('/{record}/pdfSPKQuality')
        ];
    }
}
