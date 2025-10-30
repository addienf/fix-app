<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;
use App\Filament\Resources\Sales\SpesifikasiProducts\Traits\InformasiUmum;
use App\Filament\Resources\Sales\SpesifikasiProducts\Traits\ItemRequest;
use App\Filament\Resources\Sales\SpesifikasiProducts\Traits\PenjelasanTambahan;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Traits\HasAutoNumber;
use App\Traits\HasSelectCache;
use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SpesifikasiProductResource extends Resource
{
    use SimpleFormResource,
        HasSignature,
        HasAutoNumber,
        HasSelectCache,
        InformasiUmum,
        ItemRequest,
        PenjelasanTambahan;

    protected static ?string $model = SpesifikasiProduct::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'sales/spesifikasi-produk';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'Spesifikasi Produk';
    protected static ?string $pluralLabel = 'Spesifikasi Produk';
    protected static ?string $modelLabel = 'Spesifikasi Produk';

    public static function form(Form $form): Form
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            $form
            ->schema([
                Hidden::make('status')
                    ->default('Belum Diterima'),

                self::informasiUmumSection($isEdit),

                self::itemRequestSection(),

                self::penjelasanTambahanSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'signed',
                            'role' => 'Signed by Sales Dept',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'accepted',
                            'role' => 'Accepted by Production Dept',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->accepted_signature)
                        ],
                        [
                            'prefix' => 'acknowledge',
                            'role' => 'Acknowledge by MR',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
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
                self::textColumn('urs.no_urs', 'No URS'),

                self::textColumn('urs.customer.name', 'Nama Customer'),

                self::textColumn('is_stock', 'Status Produk')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Untuk Stock' : 'Bukan Stock';
                    })
                    ->color(function ($state) {
                        return $state ? 'danger' : 'success';
                    }),

                self::textColumn('status', 'Status')
                    ->badge()
                    ->color(fn($state) => [
                        'Belum Diterima' => 'danger',
                        'Diterima' => 'warning',
                        'Diketahui MR' => 'success',
                    ][$state] ?? 'gray')
                    ->alignCenter(),

            ])
            ->filters([
                //
                SelectFilter::make('status')
                    ->options([
                        'Belum Diterima' => 'Belum Diterima',
                        'Diterima' => 'Diterima',
                        'Diketahui MR' => 'Diketahui MR',
                    ])
                    ->label('Filter Status'),

                Filter::make('is_stock')
                    ->label('Untuk Stock')
                    ->query(fn(Builder $query) => $query->where('is_stock', true)),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data Spesifikasi')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data')
                        ->successNotification(null)
                        ->after(function ($record) {
                            Notification::make()
                                ->title('Spesifikasi Product deleted successfully')
                                ->body("The Spesifikasi Product \"{$record->urs->no_urs}\" has been permanently removed.")
                                ->danger()
                                ->send();
                        }),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->tooltip('Lihat Dokumen PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->openUrlInNewTab()
                        ->visible(fn($record) => $record->status === 'Diketahui MR')
                        ->url(fn($record) => route('specProduct.preview', ['record' => $record->id])),
                ]),

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
            'index' => Pages\ListSpesifikasiProducts::route('/'),
            'create' => Pages\CreateSpesifikasiProduct::route('/create'),
            'edit' => Pages\EditSpesifikasiProduct::route('/{record}/edit'),
            'pdfSpecProduct' => Pages\viewPDF::route('/{record}/pdfSpecProduct')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'urs.customer',
                'details.product',
                'details.file',
                'pic',
            ]);
    }
}
