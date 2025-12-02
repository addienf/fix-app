<?php

namespace App\Filament\Resources\Engineering\Maintenance\ColdRoom;

use App\Filament\Resources\Engineering\Maintenance\ColdRoom\ColdRoomResource\Pages;
use App\Filament\Resources\Engineering\Maintenance\ColdRoom\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\ColdRoom\Traits\TabelChecklist;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ColdRoomResource extends Resource
{
    use Informasi, TabelChecklist, HasSignature;
    protected static ?string $model = ColdRoom::class;
    protected static ?int $navigationSort = 26;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Cold Room';
    protected static ?string $pluralLabel = 'Cold Room';
    protected static ?string $modelLabel = 'Cold Room';
    protected static ?string $slug = 'engineering/cold-room';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationBadge(): ?string
    {
        $count = ColdRoom::where('status_penyetujuan', '!=', 'Disetujui')->count();

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
                    uploadPath: 'Engineering/Maintenance/ColdRoom/Signature'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                self::textColumn('tag_no', 'Unit Name/TAG No'),

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
                        ->url(fn($record) => route('pdf.MaintenanceColdRoom', ['record' => $record->id])),
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
            'index' => Pages\ListColdRooms::route('/'),
            'create' => Pages\CreateColdRoom::route('/create'),
            'edit' => Pages\EditColdRoom::route('/{record}/edit'),
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
