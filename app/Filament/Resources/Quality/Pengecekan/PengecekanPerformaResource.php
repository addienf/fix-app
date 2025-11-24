<?php

namespace App\Filament\Resources\Quality\Pengecekan;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;
use App\Filament\Resources\Quality\Pengecekan\Traits\ChamberIdentification;
use App\Filament\Resources\Quality\Pengecekan\Traits\TabelKelengkapanMaterial;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PengecekanPerformaResource extends Resource
{
    use ChamberIdentification, TabelKelengkapanMaterial, HasSignature;
    protected static ?string $model = PengecekanPerforma::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 19;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Pengecekan Performa';
    protected static ?string $pluralLabel = 'Pengecekan Performa';
    protected static ?string $modelLabel = 'Pengecekan Performa';
    protected static ?string $slug = 'quality/pengecekan-performa';

    public static function getNavigationBadge(): ?string
    {
        $count = PengecekanPerforma::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //

                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                self::getChamberIdentificationSection($form),

                self::getTabelKelengkapanMaterialSection(),

                self::getNote(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'inspected',
                            'role' => 'Inspected by',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'accepted',
                            'role' => 'Accepted by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->accepted_signature)
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Approved by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/PengecekanPerforma/SS/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('tipe', 'Type/Model'),

                self::textColumn('volume', 'Volume'),

                self::textColumn('serial_number', 'S/N'),

                self::textColumn('status_penyelesaian', 'Status')
                    ->badge()
                    ->color(fn($state) => [
                        'Belum Diterima' => 'danger',
                        'Diterima' => 'warning',
                        'Disetujui' => 'success',
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
                        ->visible(fn($record) => $record->status_penyelesaian === 'Disetujui')
                        ->url(fn($record) => route('pdf.pengecekanPerforma', ['record' => $record->id])),
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
            'index' => Pages\ListPengecekanPerformas::route('/'),
            'create' => Pages\CreatePengecekanPerforma::route('/create'),
            'edit' => Pages\EditPengecekanPerforma::route('/{record}/edit'),
            'pdfPengecekanPerforma' => Pages\pdfPengecekanPerforma::route('/{record}/pdfPengecekanPerforma')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'penyerahanProdukJadi',
                'pic',
                'ketidaksesuaian',
                'productRelease',
                'qcPassed',
                'detail'
            ]);
    }
}
