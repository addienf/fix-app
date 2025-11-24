<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;
use App\Filament\Resources\Warehouse\PermintaanBahanWBB\Traits\DetailBahanBaku;
use App\Filament\Resources\Warehouse\PermintaanBahanWBB\Traits\InformasiUmum;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Traits\HasSignature;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PermintaanBahanResource extends Resource
{
    use HasSignature, InformasiUmum, DetailBahanBaku;
    protected static ?string $model = PermintaanBahan::class;
    protected static ?string $slug = 'warehouse/permintaan-bahan-warehouse';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Permintaan Bahan Warehouse';
    protected static ?string $pluralLabel = 'Permintaan Bahan Warehouse';
    protected static ?string $modelLabel = 'Permintaan Bahan Warehouse';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Belum Diketahui'),

                self::informasiUmumSection($form),

                self::detailBahanBakuSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'dibuat',
                            'role' => 'Dibuat Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'mengetahui',
                            'role' => 'Mengetahui',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->mengetahui_signature)
                        ],
                        [
                            'prefix' => 'diserahkan',
                            'role' => 'Diserahkan Kepada',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->mengetahui_signature) || filled($record?->diserahkan_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Warehouse/PermintaanBahan/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('permintaanBahanPro.no_surat', 'No Surat Production'),

                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d F Y'),

                self::textColumn('status', 'Status')
                    ->badge()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'Belum Diketahui' => 'danger',
                            'Diketahui' => 'warning',
                            'Diserahkan' => 'success',
                            default => 'gray',
                        };
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
                        ->url(fn($record) => route('pdf.permintaanBahan', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanBahans::route('/'),
            'create' => Pages\CreatePermintaanBahan::route('/create'),
            'edit' => Pages\EditPermintaanBahan::route('/{record}/edit'),
            'pdfPermintaanBahanWBB' => Pages\pdfPermintaanBahanWBB::route('/{record}/pdfPermintaanBahanWBB')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'permintaanBahanPro',
                'details',
                'pic',
                'pembelian',
            ]);
    }
}
