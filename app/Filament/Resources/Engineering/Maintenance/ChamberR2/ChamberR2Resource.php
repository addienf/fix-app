<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberR2;

use App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource\Pages;
use App\Filament\Resources\Engineering\Maintenance\ChamberR2\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\ChamberR2\Traits\TabelChecklist;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ChamberR2Resource extends Resource
{
    use Informasi, TabelChecklist, HasSignature;
    protected static ?string $model = ChamberR2::class;
    protected static ?int $navigationSort = 24;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Chamber R2';
    protected static ?string $pluralLabel = 'Chamber R2';
    protected static ?string $modelLabel = 'Chamber R2';
    protected static ?string $slug = 'engineering/chamber-r2';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationBadge(): ?string
    {
        $count = ChamberR2::where('status_penyetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_penyetujuan')
                    ->default('Belum Disetujui'),

                self::getInformasiSection($form),

                self::getTabelChecklistSection(),

                self::getRemarksSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'checked',
                            'role' => 'Checked By',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Approved By',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Engineering/Maintenance/ChamberR2/Signature'
                ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tag_no', 'CTC Name/TAG No'),

                self::textColumn('status_penyetujuan', 'Status')
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
                        ->visible(fn($record) => $record->status_penyetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.walkInChamberR2', ['record' => $record->id])),
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
            'index' => Pages\ListChamberR2S::route('/'),
            'create' => Pages\CreateChamberR2::route('/create'),
            'edit' => Pages\EditChamberR2::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spkService',
                'detail',
                'pic'
            ]);
    }
}
