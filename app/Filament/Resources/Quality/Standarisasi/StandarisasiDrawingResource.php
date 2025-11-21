<?php

namespace App\Filament\Resources\Quality\Standarisasi;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;
use App\Filament\Resources\Quality\Standarisasi\Traits\Details;
use App\Filament\Resources\Quality\Standarisasi\Traits\IdentitasGambarKerja;
use App\Filament\Resources\Quality\Standarisasi\Traits\InformasiUmum;
use App\Filament\Resources\Quality\Standarisasi\Traits\KomponenGambar;
use App\Filament\Resources\Quality\Standarisasi\Traits\SpesifikasiTeknis;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
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

class StandarisasiDrawingResource extends Resource
{
    use InformasiUmum, IdentitasGambarKerja, Details, KomponenGambar, SpesifikasiTeknis, HasSignature;
    protected static ?string $model = StandarisasiDrawing::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Standarisasi Gambar Kerja';
    protected static ?string $pluralLabel = 'Standarisasi Gambar Kerja';
    protected static ?string $modelLabel = 'Standarisasi Gambar Kerja';
    protected static ?string $slug = 'quality/standarisasi-gambar-kerja';

    public static function getNavigationBadge(): ?string
    {
        $count = StandarisasiDrawing::where('status_pemeriksaan', '!=', 'Diperiksa')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_pemeriksaan')
                    ->default('Belum Diperiksa'),

                static::informasiUmumSection($form),

                static::identifikasiGambarKerjaSection(),

                static::spesifikasiTeknisSection(),

                static::kopmponenGambarSection(),

                static::detailsSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'create',
                            'role' => 'Dibuat Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'check',
                            'role' => 'Diperiksa Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->check_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/StandarisasiDrawing/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk.no_spk')
                    ->label('No SPK Marketing'),

                TextColumn::make('serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks.no_seri')
                    ->label('No Seri'),

                self::textColumn('tanggal', 'Tanggal')
                    ->date('d F Y'),

                self::textColumn('jenis_gambar', 'Jenis Gambar')
                    ->formatStateUsing(function (string $state): string {
                        return config('standarisasi.jenis_gambar')[$state] ?? $state;
                    }),

                self::textColumn('format_gambar', 'Format Gambar'),

                TextColumn::make('status_pemeriksaan')
                    ->label('Status Pemeriksaan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Diperiksa' ? 'success' : 'danger'
                    )
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_pemeriksaan === 'Diperiksa')
                        ->url(fn($record) => route('pdf.StandarisasiDrawing', ['record' => $record->id])),
                    Action::make('lampiran_view')
                        ->label(_('Lihat Lampiran'))
                        ->icon('heroicon-o-clipboard')
                        ->color('primary')
                        ->visible(fn($record) => !empty($record->detail?->lampiran))
                        ->url(fn($record) => route('pdf.StandarisasiDrawingLampiran', ['record' => $record->id])),
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
            'index' => Pages\ListStandarisasiDrawings::route('/'),
            'create' => Pages\CreateStandarisasiDrawing::route('/create'),
            'edit' => Pages\EditStandarisasiDrawing::route('/{record}/edit'),
            'pdfStandarisasiDrawing' => Pages\pdfStandarisasiDrawing::route('/{record}/pdfStandarisasiDrawing')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                'identitas',
                'pic',
                'detail',
                'pemeriksaan',
            ]);
    }
}
