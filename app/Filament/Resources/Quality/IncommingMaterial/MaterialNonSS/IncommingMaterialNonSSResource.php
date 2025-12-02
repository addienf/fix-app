<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;
use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\Traits\HasilPemeriksaan;
use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\Traits\InformasiUmum;
use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\Traits\Summary;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IncommingMaterialNonSSResource extends Resource
{
    use InformasiUmum, HasilPemeriksaan, Summary, HasSignature;
    protected static ?string $model = IncommingMaterialNonSS::class;
    protected static ?string $slug = 'quality/incoming-material-non-stainless-steel';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 18;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Incoming Material Non SS';
    protected static ?string $pluralLabel = 'Incoming Material Non Stainless Steel';
    protected static ?string $modelLabel = 'Incoming Material Non Stainless Steel';

    public static function getNavigationBadge(): ?string
    {
        $count = IncommingMaterialNonSS::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                self::informasiUmumSection($form),

                self::hasilPemeriksaanSection(),

                self::summarySection(),

                self::noBatchSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'checked',
                            'role' => 'Checked By',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'accepted',
                            'role' => 'Accepted By',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->accepted_signature)
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Approved By',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/IncommingMaterial/NonSS/Signatures'
                ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('no_po', 'No PO'),

                self::textColumn('supplier', 'Supplier'),

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
                        ->tooltip('Lihat Dokumen PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penyelesaian === 'Disetujui')
                        ->url(fn($record) => route('pdf.incomingMaterialNonSS', ['record' => $record->id])),
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
            'index' => Pages\ListIncommingMaterialNonSS::route('/'),
            'create' => Pages\CreateIncommingMaterialNonSS::route('/create'),
            'edit' => Pages\EditIncommingMaterialNonSS::route('/{record}/edit'),
            'pdfIncommingMaterialNonSS' => Pages\pdfIncommingMaterialNonSS::route('/{record}/pdfIncommingMaterialNonSS')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'permintaanPembelian',
                'detail',
                'pic',
                'summary',
            ]);
    }
}
