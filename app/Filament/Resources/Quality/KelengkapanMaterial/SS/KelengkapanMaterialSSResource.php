<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS;

use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\Pages;
use App\Filament\Resources\Quality\KelengkapanMaterial\SS\Traits\ChamberIdentification;
use App\Filament\Resources\Quality\KelengkapanMaterial\SS\Traits\TabelKelengkapanMaterial;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KelengkapanMaterialSSResource extends Resource
{
    use ChamberIdentification, TabelKelengkapanMaterial, HasSignature, SimpleFormResource;
    protected static ?string $model = KelengkapanMaterialSS::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 14;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Kelengkapan Material SS';
    protected static ?string $pluralLabel = 'Kelengkapan Material Stainless Steel';
    protected static ?string $modelLabel = 'Kelengkapan Material Stainless Steel';
    protected static ?string $slug = 'quality/kelengkapan-material';

    public static function getNavigationBadge(): ?string
    {
        $count = KelengkapanMaterialSS::where('status_penyelesaian', '!=', 'Disetujui')->count();

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

                self::getTabelKelengkapanMaterialSection2(),

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
                    uploadPath: 'Quality/KelengkapanMaterial/SS/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'NO SPK'),

                self::textColumn('tipe', 'Type/Model'),

                self::textColumn('ref_document', 'Ref Document'),

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
                        ->url(fn($record) => route('pdf.kelengkapanMaterialSS', ['record' => $record->id])),
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
            'index' => Pages\ListKelengkapanMaterialSS::route('/'),
            'create' => Pages\CreateKelengkapanMaterialSS::route('/create'),
            'edit' => Pages\EditKelengkapanMaterialSS::route('/{record}/edit'),
            'pdfKelengkapanMaterialSS' => Pages\pdfKelengkapanMaterialSS::route('/{record}/pdfKelengkapanMaterialSS')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spk',
                'details',
                'pic'
            ]);
    }
}
