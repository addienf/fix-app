<?php

namespace App\Filament\Resources\Production\Jadwal;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;
use App\Filament\Resources\Production\Jadwal\Traits\DetailJadwalProduksi;
use App\Filament\Resources\Production\Jadwal\Traits\IdentifikasiProduk;
use App\Filament\Resources\Production\Jadwal\Traits\InformasiUmum;
use App\Filament\Resources\Production\Jadwal\Traits\KebutuhanBahan;
use App\Filament\Resources\Production\Jadwal\Traits\TimelineProduksi;
use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Traits\HasSignature;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JadwalProduksiResource extends Resource
{
    use InformasiUmum, IdentifikasiProduk, DetailJadwalProduksi, KebutuhanBahan, TimelineProduksi, HasSignature;
    protected static ?string $model = JadwalJadwalProduksi::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Perencanaan Produksi';
    protected static ?string $pluralLabel = 'Perencanaan Produksi';
    protected static ?string $modelLabel = 'Perencanaan  Produksi';
    protected static ?string $slug = 'produksi/jadwal-produksi';

    public static function getNavigationBadge(): ?string
    {
        $count = JadwalJadwalProduksi::where('status_persetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_persetujuan')
                    ->default('Belum Disetujui'),

                self::informasiUmumSection(),

                self::identifikasiProdukSection(),

                self::detailJadwalProduksiSection(),

                self::standardSection(),

                self::kebutuhanBahanSection(),

                self::timelineProduksiSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'create',
                            'role' => 'Dibuat Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'approve',
                            'role' => 'Disetujui Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->approve_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Production/Jadwal/Signatures'
                )

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'No SPK'),

                self::textColumn('pic_name', 'Nama PIC'),

                self::textColumn('identifikasiProduks.no_seri', 'Nomor Seri'),

                self::textColumn('tanggal', 'Tanggal Dibuat')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d F Y')),

                TextColumn::make('status_persetujuan')
                    ->label('Status Persetujuan')
                    ->badge()
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
                ActionsActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data Spesifikasi')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->tooltip('Lihat Dokumen PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_persetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.jadwalProduksi', ['record' => $record->id])),
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
            'index' => Pages\ListJadwalProduksis::route('/'),
            'create' => Pages\CreateJadwalProduksi::route('/create'),
            'edit' => Pages\EditJadwalProduksi::route('/{record}/edit'),
            'pdfJadwalProduksi' => Pages\pdfJadwalProduksi::route('/{record}/pdfJadwalProduksi')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spk',
                'identifikasiProduks',
                'details',
                'sumbers',
                'pic',
            ]);
    }
}
