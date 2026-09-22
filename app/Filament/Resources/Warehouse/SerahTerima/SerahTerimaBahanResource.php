<?php

namespace App\Filament\Resources\Warehouse\SerahTerima;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;
use App\Filament\Resources\Warehouse\SerahTerima\Traits\DetailBahanBaku;
use App\Filament\Resources\Warehouse\SerahTerima\Traits\InformasiUmum;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Traits\HasSignature;
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
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Serah Terima Bahan';
    protected static ?string $pluralLabel = 'Serah Terima Bahan';
    protected static ?string $modelLabel = 'Serah Terima Bahan';
    protected static ?string $slug = 'warehouse/serah-terima-bahan';

    public static function getNavigationBadge(): ?string
    {
        $count = cache()->remember(
            key: 'nav_badge_serah_terima_bahan',
            ttl: now()->addSeconds(30),
            callback: fn() => SerahTerimaBahan::where('status_penerimaan', '!=', 'Diterima')->count()
        );
        return $count > 0 ? (string) $count : null;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                static::informasiUmumSection($form),
                static::detailBahanSection(),
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
                self::textColumn('no_surat', 'Nomor Surat Serah Terima Bahan'),
                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d F Y'),
                TextColumn::make('status_penerimaan')
                    ->label('Status Penerimaan')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Diterima'      => 'success',
                        'Sedang Diproses' => 'warning',
                        default         => 'danger',
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
                        ->tooltip('Edit Serah Terima Barang')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(__('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima')
                        ->url(fn($record) => route('pdf.serahTerima', ['record' => $record->id]))
                        ->openUrlInNewTab(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
        return parent::getEloquentQuery();
    }
}
