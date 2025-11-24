<?php

namespace App\Filament\Resources\Quality\Release;

use App\Filament\Resources\Quality\Release\ProductReleaseResource\Pages;
use App\Filament\Resources\Quality\Release\ProductReleaseResource\RelationManagers;
use App\Filament\Resources\Quality\Release\Traits\Informasi;
use App\Models\Quality\Release\ProductRelease;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductReleaseResource extends Resource
{
    use HasSignature, Informasi;
    protected static ?string $model = ProductRelease::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Product Release';
    protected static ?string $pluralLabel = 'Product Release';
    protected static ?string $modelLabel = 'Product Release';
    protected static ?string $slug = 'quality/product-release';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Belum Dikonfirmasi'),

                self::getInformasiSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'dibuat',
                            'role' => 'Dibuat Oleh,',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'dikonfirmasi',
                            'role' => 'Dikonfirmasi Oleh,',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->dikonfirmasi_signature)
                        ],
                        [
                            'prefix' => 'diterima',
                            'role' => 'Diterima Oleh,',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->dikonfirmasi_signature) || filled($record?->diterima_signature)
                        ],
                        [
                            'prefix' => 'diketahui',
                            'role' => 'Diketahui Oleh,',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->dikonfirmasi_signature) || blank($record?->diterima_signature) || filled($record?->diketahui_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/Release/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('no_order_release', 'Release Order No'),

                self::textColumn('product', 'The Product'),

                self::textColumn('status', 'Status')
                    ->badge()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'Belum Dikonfirmasi' => 'danger',
                            'Dikonfirmasi' => 'warning',
                            'Diterima'     => 'warning',
                            'Diketahui'    => 'success',
                            default        => 'gray',
                        };
                    }),
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
                        ->visible(fn($record) => $record->status === 'Diketahui')
                        ->url(fn($record) => route('pdf.productRelease', ['record' => $record->id])),
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
            'index' => Pages\ListProductReleases::route('/'),
            'create' => Pages\CreateProductRelease::route('/create'),
            'edit' => Pages\EditProductRelease::route('/{record}/edit'),
        ];
    }
}
