<?php

namespace App\Filament\Resources\Purchasing\Permintaan;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;
use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Traits\DetailBahanBaku;
use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Traits\NoSurat;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasSignature;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PermintaanPembelianResource extends Resource
{
    use NoSurat, DetailBahanBaku, HasSignature;
    protected static ?string $model = PermintaanPembelian::class;
    protected static ?string $slug = 'purchasing/permintaan-pembelian';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationGroup = 'Purchasing';
    protected static ?string $navigationLabel = 'Permintaan Pembelian';
    protected static ?string $pluralLabel = 'Permintaan Pembelian';
    protected static ?string $modelLabel = 'Permintaan Pembelian';
    public static function getNavigationBadge(): ?string
    {
        $count = PermintaanPembelian::where('status_persetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Hidden::make('status_persetujuan')
                    ->default('Belum Disetujui'),

                self::noSuratSection(),

                self::detailBahanBakuSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'create',
                            'role' => 'Dibuat Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'knowing',
                            'role' => 'Mengetahui',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->knowing_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Purchasing/PermintaanPembelian/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('permintaanBahanWBB.no_surat', 'No Surat WBB'),

                self::textColumn('status_persetujuan', 'Status Persetujuan')
                    ->badge()
                    ->searchable(false)
                    ->sortable(false)
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
                    EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data SPK Marketing')
                        ->color('info'),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->tooltip('Lihat Dokumen PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_persetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.PermintaanPembelian', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanPembelians::route('/'),
            'create' => Pages\CreatePermintaanPembelian::route('/create'),
            'edit' => Pages\EditPermintaanPembelian::route('/{record}/edit'),
            'pdfPermintaanPembelian' => Pages\pdfPermintaanPembelian::route('/{record}/pdfPermintaanPembelian')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'permintaanBahanWBB',
                'details',
                'pic',
                'materialNonSS',
                'materialSS',
                'incommingMaterial'
            ]);
    }
}
