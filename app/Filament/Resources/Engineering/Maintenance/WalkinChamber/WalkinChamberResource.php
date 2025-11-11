<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits\TabelChecklist;
use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WalkinChamberResource extends Resource
{
    use Informasi, TabelChecklist, HasSignature;
    protected static ?string $model = WalkinChamber::class;
    protected static ?int $navigationSort = 23;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Walk-in Chamber';
    protected static ?string $pluralLabel = 'Walk-in Chamber';
    protected static ?string $modelLabel = 'Walk-in Chamber';
    protected static ?string $slug = 'engineering/walkin-chamber';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    public static function getNavigationBadge(): ?string
    {
        $count = WalkinChamber::where('status_penyetujuan', '!=', 'Disetujui')->count();

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
                    uploadPath: 'Engineering/Maintenance/WalkinChamber/Signature'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tag_no', 'WTC Name/TAG No'),

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
                        ->url(fn($record) => route('pdf.walkInChamberR1', ['record' => $record->id])),
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
            'index' => Pages\ListWalkinChambers::route('/'),
            'create' => Pages\CreateWalkinChamber::route('/create'),
            'edit' => Pages\EditWalkinChamber::route('/{record}/edit'),
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
