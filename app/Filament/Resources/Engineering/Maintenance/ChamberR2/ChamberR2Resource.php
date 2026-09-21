<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberR2;

use App\Filament\Resources\Engineering\Maintenance\ChamberR2\ChamberR2Resource\Pages;
use App\Filament\Resources\Engineering\Maintenance\ChamberR2\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\ChamberR2\Traits\TabelChecklist;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Traits\HasSignature;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ButtonAction;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Str;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class ChamberR2Resource extends Resource
{
    use Informasi, TabelChecklist, HasSignature;
    protected static ?string $model = ChamberR2::class;
    protected static ?int $navigationSort = 24;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Stability Chamber';
    protected static ?string $pluralLabel = 'Stability Chamber';
    protected static ?string $modelLabel = 'Stability Chamber';
    protected static ?string $slug = 'engineering/stability-chamber';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

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
                        // static::signatureSection2(
                        //     [
                        //         [
                        //             'prefix' => 'checked',
                        //             'role' => 'Checked By',
                        //             'hideLogic' => fn($operation) => $operation === 'edit',
                        //         ],
                        //     ],
                        //     title: 'PIC',
                        //     uploadPath: 'Engineering/Maintenance/StabilityChamber/Signature'
                        // )
                        //     ->hiddenOn('edit'),

                        Section::make('PIC')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ])
                                    ->schema([
                                        self::textInput('checked_name', 'Approved By')
                                            ->required(),

                                        self::dateInput('checked_date', 'Tanggal')
                                            ->required(),

                                        self::signatureInput(
                                            "checked_signature",
                                            '',
                                            'Engineering/Maintenance/StabilityChamber/Signature'
                                        )
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                            ])
                    ])->hiddenOn('edit'),

                Section::make('Pelanggan')
                    ->collapsible()
                    ->relationship('pic')
                    ->hidden(function ($get, $operation) {
                        return $operation === 'create'
                            || filled($get('pic.approved_signature'));
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data) {

                        if (!empty($data['approved_signature_draw'])) {
                            $data['approved_signature'] = $data['approved_signature_draw'];
                        }

                        if (!empty($data['approved_signature_upload'])) {
                            $data['approved_signature'] = $data['approved_signature_upload'];
                        }

                        unset($data['approved_signature_draw']);
                        unset($data['approved_signature_upload']);

                        return $data;
                    })
                    ->schema([

                        Grid::make(2)
                            ->schema([
                                self::getIsStock(),

                                self::textInput('approved_name', 'Nama Pelanggan')
                                    ->required(),

                                DatePicker::make("approved_date")
                                    ->label('Tanggal')
                                    ->default(now())
                                    ->required(),
                            ]),

                        self::signatureInput('approved_signature_draw', '', 'Engineering/Maintenance/StabilityChamber/Signature')
                            ->visible(fn($get) => $get('ttd_method') === 'draw'),

                        FileUpload::make('approved_signature_upload')
                            ->label('Upload Scan Tanda Tangan')
                            ->image()
                            ->directory('Engineering/Maintenance/StabilityChamber/Signature')
                            ->visible(fn($get) => $get('ttd_method') === 'upload'),
                    ]),

                Section::make('Link Tanda Tangan')
                    ->hidden(function ($get, $operation) {
                        return $operation === 'create'
                            || filled($get('pic.pelanggan_ttd'));
                    })
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Actions::make([
                                    ButtonAction::make('generate_link')
                                        ->label('Generate Link')
                                        ->icon('heroicon-o-link')
                                        ->action(function ($record) {
                                            $record->pic->update([
                                                'sign_token' => Str::random(8),
                                                'sign_token_expires_at' => now()->addDays(3),
                                            ]);
                                        }),
                                ]),

                                Placeholder::make('sign_link')
                                    ->label(false)
                                    ->content(function ($record) {
                                        return $record->pic?->sign_token
                                            ? route('signature.show', [
                                                'type' => 'stability-chamber',
                                                'token' => $record->pic->sign_token,
                                            ])
                                            : 'Belum ada link dibuat';
                                    }),
                            ])
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spkService.no_spk_service', 'No SPK Service'),
                self::textColumn('spkService.perusahaan', 'Nama Perusahaan'),
                self::textColumn('tag_no', 'CTC Name/TAG No'),
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
                        // ->visible(fn($record) => $record->status_penyetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.stabilityChamber', ['record' => $record->id])),
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

    private static function getIsStock()
    {
        return
            ButtonGroup::make('ttd_method')
            ->label('Metode Tanda Tangan')
            ->options([
                'draw' => 'Tanda tangan langsung',
                'upload' => 'Upload scan tanda tangan',
            ])
            ->default('draw')
            ->live()
            ->dehydrated(false)
            ->required()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }
}
