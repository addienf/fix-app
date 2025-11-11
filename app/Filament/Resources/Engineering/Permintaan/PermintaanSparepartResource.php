<?php

namespace App\Filament\Resources\Engineering\Permintaan;

use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\Pages;
use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\RelationManagers;
use App\Filament\Resources\Engineering\Permintaan\Traits\InformasiUmum;
use App\Filament\Resources\Engineering\Permintaan\Traits\ListSparepart;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\SPK\SPKService;
use App\Services\SignatureUploader;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PermintaanSparepartResource extends Resource
{
    use InformasiUmum, ListSparepart, HasSignature;
    protected static ?string $model = PermintaanSparepart::class;
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Permintaan Spareparts';
    protected static ?string $pluralLabel = 'Permintaan Spareparts';
    protected static ?string $modelLabel = 'Permintaan Spareparts';
    protected static ?string $slug = 'engineering/permintaan-spareparts';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationBadge(): ?string
    {
        $count = PermintaanSparepart::where('status_penyerahan', '!=', 'Diserahkan')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penyerahan')
                    ->default('Belum Diketahui'),

                self::getInformasiUmumSection($form),

                self::getListSparepartSection(),

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
                    uploadPath: 'Engineering/PermintaanSparepart/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('spkService.no_spk_service', 'No SPK Service'),

                self::textColumn('tanggal', 'Tanggal')->date('d F Y'),

                self::textColumn('status_penyerahan', 'Status')
                    ->badge()
                    ->color(fn($state) => [
                        'Belum Diketahui' => 'danger',
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
                        ->tooltip('Edit Data Spesifikasi')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penyerahan === 'Diserahkan')
                        ->url(fn($record) => route('pdf.sparepartAlatKerja', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanSpareparts::route('/'),
            'create' => Pages\CreatePermintaanSparepart::route('/create'),
            'edit' => Pages\EditPermintaanSparepart::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spkService',
                'details',
                'pic',
            ]);
    }
}
