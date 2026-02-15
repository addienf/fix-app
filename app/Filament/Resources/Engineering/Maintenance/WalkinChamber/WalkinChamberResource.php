<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits\TabelChecklist;
use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
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
    protected static ?string $navigationLabel = 'Walk in Test Chamber';
    protected static ?string $pluralLabel = 'Walk in Test Chamber';
    protected static ?string $modelLabel = 'Walk in Test Chamber';
    protected static ?string $slug = 'engineering/walkin-test-chamber';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    // public static function getNavigationBadge(): ?string
    // {
    //     $count = WalkinChamber::where('status_penyetujuan', '!=', 'Disetujui')->count();

    //     return $count > 0 ? (string) $count : null;
    // }

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

                Section::make('Customer Info')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        static::signatureSection2(
                            [
                                [
                                    'prefix' => 'checked',
                                    'role' => 'Checked By',
                                    'hideLogic' => fn($operation) => $operation === 'edit',
                                ],
                            ],
                            title: 'PIC',
                            uploadPath: 'Engineering/Maintenance/WalkinTestChamber/Signature'
                        )
                            ->hiddenOn('edit'),

                        Section::make('PIC')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ])
                                    ->schema([
                                        self::textInput('approved_name', 'Approved By')
                                            ->required(false),

                                        self::dateInput('approved_date', 'Tanggal')
                                            ->required(false),

                                        self::signatureInput(
                                            "approved_signature",
                                            '',
                                            'Engineering/Maintenance/WalkinTestChamber/Signature'
                                        )
                                            ->required(false)
                                            ->columnSpanFull(),
                                    ])
                            ])->hiddenOn('create')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tag_no', 'WTC Name/TAG No'),

                // self::textColumn('status_penyetujuan', 'Status')
                //     ->badge()
                //     ->color(
                //         fn($state) =>
                //         $state === 'Disetujui' ? 'success' : 'danger'
                //     )
                //     ->alignCenter(),
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
                        ->label(_('Download PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        // ->visible(fn($record) => $record->status_penyetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.walkInChamber', ['record' => $record->id])),
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
