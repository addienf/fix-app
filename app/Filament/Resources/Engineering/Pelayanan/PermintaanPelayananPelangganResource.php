<?php

namespace App\Filament\Resources\Engineering\Pelayanan;

use App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource\Pages;
use App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource\RelationManagers;
use App\Filament\Resources\Engineering\Pelayanan\Traits\IdentitasAlat;
use App\Filament\Resources\Engineering\Pelayanan\Traits\Informasi;
use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
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

class PermintaanPelayananPelangganResource extends Resource
{
    use Informasi, IdentitasAlat, HasSignature;
    protected static ?string $model = PermintaanPelayananPelanggan::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Customer Care';
    protected static ?string $navigationLabel = 'Permintaan Pelayanan Pelanggan';
    protected static ?string $pluralLabel = 'Permintaan Pelayanan Pelanggan';
    protected static ?string $modelLabel = 'Permintaan Pelayanan Pelanggan';
    protected static ?string $slug = 'engineering/permintaan-pelayanan-pelanggan';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Belum Diterima'),

                self::getInformasiSection(),

                self::getJenisPermintaan(),

                self::getIdentitasAlatSection(),

                self::getPelaksanaan(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'diketahui',
                            'role' => 'Diketahui Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'diterima',
                            'role' => 'Diterima Oleh,',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->diterima_signature)
                        ],
                        [
                            'prefix' => 'dibuat',
                            'role' => 'Dibuat Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->diterima_signature) || filled($record?->dibuat_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Engineering/PelayananPelanggan/Signatures'
                )

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('no_form', 'Form No'),

                self::textColumn('tanggal', 'Tanggal')->date('d F Y'),

                self::textColumn('status', 'Status')
                    ->badge()
                    ->color(fn($state) => [
                        'Belum Diterima' => 'danger',
                        'Diterima' => 'warning',
                        'Dibuat' => 'success',
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
                        ->tooltip('Edit Data Spesifikasi')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status === 'Dibuat')
                        ->url(fn($record) => route('pdf.PelayananPelanggan', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanPelayananPelanggans::route('/'),
            'create' => Pages\CreatePermintaanPelayananPelanggan::route('/create'),
            'edit' => Pages\EditPermintaanPelayananPelanggan::route('/{record}/edit'),
        ];
    }
}
