<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian;

use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\Pages;
use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\RelationManagers;
use App\Filament\Resources\Quality\Ketidaksesuaian\Traits\DetailKetidaksesuaian;
use App\Filament\Resources\Quality\Ketidaksesuaian\Traits\InformasiUmum;
use App\Filament\Resources\Quality\Ketidaksesuaian\Traits\SyaratDanketentuan;
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KetidaksesuaianResource extends Resource
{
    use InformasiUmum, DetailKetidaksesuaian, SyaratDanketentuan, HasSignature;
    protected static ?string $model = Ketidaksesuaian::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 19;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Ketidaksesuaian Produk & Material';
    protected static ?string $pluralLabel = 'Ketidaksesuaian Produk & Material';
    protected static ?string $modelLabel = 'Ketidaksesuaian Produk & Material';
    protected static ?string $slug = 'quality/ketidaksesuaian-produk-dan-material';

    public static function form(Form $form): Form
    {
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Belum Diterima'),

                self::informasiUmumSection($form),

                self::getDetailKetidaksesuaianSection(),

                self::getSyaratDanketentuanSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'pelapor',
                            'role' => 'Dilaporkan Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'diterima',
                            'role' => 'Diterima Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->diterima_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/Ketidaksesuaian/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tanggal', 'Tanggal')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d F Y')),

                self::textColumn('pelapor', 'Pelapor'),

                self::textColumn('nama_perusahaan', 'Nama Perusahaan'),

                self::textColumn('status', 'Status')
                    ->badge()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'Belum Diterima' => 'danger',
                            'Diterima' => 'success',
                            default => 'gray',
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
                        ->visible(fn($record) => $record->status === 'Diterima')
                        ->url(fn($record) => route('pdf.ketidaksesuaian', ['record' => $record->id])),
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
            'index' => Pages\ListKetidaksesuaians::route('/'),
            'create' => Pages\CreateKetidaksesuaian::route('/create'),
            'edit' => Pages\EditKetidaksesuaian::route('/{record}/edit'),
        ];
    }
}
