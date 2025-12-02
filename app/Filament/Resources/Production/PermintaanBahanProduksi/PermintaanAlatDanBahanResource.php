<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;
use App\Filament\Resources\Production\PermintaanBahanProduksi\Traits\DetailBahanBaku;
use App\Filament\Resources\Production\PermintaanBahanProduksi\Traits\InformasiUmum;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Traits\HasSignature;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class PermintaanAlatDanBahanResource extends Resource
{
    use InformasiUmum, DetailBahanBaku, HasSignature;
    protected static ?string $model = PermintaanAlatDanBahan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Permintaan Bahan dan Alat Produksi';
    protected static ?string $pluralLabel = 'Permintaan Bahan dan Alat Produksi';
    protected static ?string $modelLabel = 'Permintaan Bahan dan Alat Produksi';
    protected static ?string $slug = 'produksi/permintaan-alat-dan-bahan';

    public static function getNavigationBadge(): ?string
    {
        $count = PermintaanAlatDanBahan::where('status_penyerahan', '!=', 'Diserahkan')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penyerahan')
                    ->default('Belum Diserahkan'),

                Hidden::make('status')
                    ->disabledOn('edit')
                    ->default('Belum Diproses'),

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
                            'prefix' => 'diketahui',
                            'role' => 'Diketahui Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->diketahui_signature)
                        ],
                        [
                            'prefix' => 'diserahkan',
                            'role' => 'Diserahkan Kepada',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->diketahui_signature) || filled($record?->diserahkan_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Production/PermintaanBahan/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('jadwalProduksi.no_surat', 'Nomor Surat Perencanaan Produksi'),

                self::textColumn('no_surat', 'Nomor Surat'),

                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d F Y'),

                self::textColumn('status', 'Status Stock Barang')
                    ->badge()
                    ->color(fn($state) => [
                        'Tidak Tersedia' => 'danger',
                        'Tersedia' => 'success',
                    ][$state] ?? 'gray')
                    ->alignCenter(),

                self::textColumn('status_penyerahan', 'Status')
                    ->badge()
                    ->color(fn($state) => [
                        'Belum Diserahkan' => 'danger',
                        'Diketahui' => 'warning',
                        'Diserahkan' => 'success',
                    ][$state] ?? 'gray')
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
                        ->visible(fn($record) => $record->status_penyerahan === 'Diserahkan')
                        ->url(fn($record) => route('pdf.permintaanAlatBahan', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanAlatDanBahans::route('/'),
            'create' => Pages\CreatePermintaanAlatDanBahan::route('/create'),
            'edit' => Pages\EditPermintaanAlatDanBahan::route('/{record}/edit'),
            'pdfPermintaanAlatdanBahan' => Pages\pdfPermintaanAlatdanBahan::route('/{record}/pdfPermintaanAlatdanBahan')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'jadwalProduksi',
                'details',
                'pic',
                'permintaanBahanWBB',
            ]);
    }
}
