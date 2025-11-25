<?php

namespace App\Filament\Resources\Engineering\SPK;

use App\Filament\Resources\Engineering\SPK\SPKServiceResource\Pages;
use App\Filament\Resources\Engineering\SPK\Traits\DeskripsiPekerjaan;
use App\Filament\Resources\Engineering\SPK\Traits\InformasiUmum;
use App\Filament\Resources\Engineering\SPK\Traits\Pemeriksaan;
use App\Filament\Resources\Engineering\SPK\Traits\Petugas;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SPKServiceResource extends Resource
{
    use InformasiUmum, DeskripsiPekerjaan, Pemeriksaan, Petugas, HasSignature;
    protected static ?string $model = SPKService::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'SPK Service';
    protected static ?string $pluralLabel = 'SPK Service';
    protected static ?string $modelLabel = 'SPK Service';
    protected static ?string $slug = 'engineering/spk-service';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationBadge(): ?string
    {
        $count = SPKService::where('status', '!=', 'Selesai')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Belum Selesai'),

                self::getInformasiUmumSection(),

                self::getDeskripsiPekerjaanSection(),

                self::getPemeriksaanSection(),

                self::getPelaksanaanSection(),

                // self::getPemeriksaanSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'dikonfirmasi',
                            'role' => 'Dikonfirmasi Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'dibuat',
                            'role' => 'Dibuat Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->dibuat_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Engineering/SPK/Signatures'
                ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('pelayananPelanggan.no_form', 'No Compaint Form'),

                self::textColumn('no_spk_service', 'No SPK Service'),

                self::textColumn('perusahaan', 'Nama Perusahaan'),

                self::textColumn('status', 'Status')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Selesai' ? 'success' : 'danger'
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
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status === 'Selesai')
                        ->url(fn($record) => route('pdf.spkService', ['record' => $record->id])),
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
            'index' => Pages\ListSPKServices::route('/'),
            'create' => Pages\CreateSPKService::route('/create'),
            'edit' => Pages\EditSPKService::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'pelayananPelanggan',
                'petugas',
                'details',
                'beritaAcara',
                'pic',
                'permintaanSparepart',
                'walkinChamber',
                'chamberR2',
                'refrigerator',
                'coldRoom',
                'rissing',
                'walkinG2',
                'chamberG2',
                'service',
            ]);
    }
}
