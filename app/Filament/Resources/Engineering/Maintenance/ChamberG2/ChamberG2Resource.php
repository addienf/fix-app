<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberG2;

use App\Filament\Resources\Engineering\Maintenance\ChamberG2\ChamberG2Resource\Pages;
use App\Filament\Resources\Engineering\Maintenance\ChamberG2\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\ChamberG2\Traits\TabelChecklist;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Traits\HasSignature;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
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
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class ChamberG2Resource extends Resource
{
    use Informasi, TabelChecklist, HasSignature;
    protected static ?string $model = ChamberG2::class;
    protected static ?int $navigationSort = 29;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Chamber G2';
    protected static ?string $pluralLabel = 'Chamber G2';
    protected static ?string $modelLabel = 'Chamber G2';
    protected static ?string $slug = 'engineering/chamber-g2';
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

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->hidden(fn($record) => filled($record?->pic?->checked_signature))
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                            'lg' => 2,
                        ])
                            ->schema([
                                self::textInput('checked_name', 'Checked By')
                                    ->required(false),

                                self::dateInput('checked_date', 'Tanggal')
                                    ->required(false),

                                self::signatureInput(
                                    "checked_signature",
                                    '',
                                    'Engineering/Maintenance/ChamberG2/Signature'
                                )
                                    ->required(false)
                                    ->columnSpanFull(),
                            ])
                    ]),

                Section::make('Pelanggan')
                    ->collapsible()
                    ->relationship('pic')
                    ->hidden(function ($get, $operation) {
                        return $operation === 'create'
                            || filled($get('pic.approved_signature'))
                            || blank($get('pic.checked_signature'));
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

                        Grid::make([
                            'default' => 2,
                            'md' => 2,
                            'lg' => 2,
                        ])
                            ->schema([
                                self::getIsStock(),

                                self::textInput('approved_name', 'Nama Pelanggan')
                                    ->required(
                                        fn($get) => filled($get('pic.checked_signature'))
                                            && blank($get('pic.approved_signature'))
                                    ),

                                DatePicker::make("approved_date")
                                    ->label('Tanggal')
                                    ->default(now())
                                    ->required(
                                        fn($get) => filled($get('pic.checked_signature'))
                                            && blank($get('pic.approved_signature'))
                                    ),
                            ]),

                        self::signatureInput('approved_signature_draw', '', 'Engineering/Maintenance/WalkinTestChamber/Signature')
                            ->visible(fn($get) => $get('ttd_method') === 'draw')
                            ->required(
                                fn($get) =>
                                filled($get('pic.checked_signature'))
                            ),

                        FileUpload::make('approved_signature_upload')
                            ->label('Upload Scan Tanda Tangan')
                            ->image()
                            ->directory('Engineering/Maintenance/WalkinTestChamber/Signature')
                            ->visible(fn($get) => $get('ttd_method') === 'upload')
                            ->required(
                                fn($get) =>
                                filled($get('pic.checked_signature'))
                            ),
                    ]),

                Section::make('Link Tanda Tangan')
                    ->hidden(
                        fn($get, $operation) =>
                        $operation === 'create' || filled($get('pic.approved_signature'))
                    )
                    ->schema([
                        Grid::make(1)
                            ->schema([

                                TextInput::make('signature_link')
                                    ->label('')
                                    ->readOnly()
                                    ->dehydrated(false)
                                    ->formatStateUsing(
                                        fn($record) =>
                                        $record?->pic?->sign_token
                                            ? route('signature.show', [
                                                'type' => 'chamber-g2',
                                                'token' => $record->pic->sign_token,
                                            ])
                                            : null
                                    )
                                    ->placeholder('Belum ada link dibuat')
                                    ->suffixActions([
                                        ButtonAction::make('generate')
                                            ->icon('heroicon-o-link')
                                            ->tooltip('Generate Link')
                                            ->action(function ($record, $set) {
                                                $record->pic->update([
                                                    'sign_token' => Str::random(8),
                                                    'sign_token_expires_at' => now()->addDays(3),
                                                ]);

                                                $set(
                                                    'signature_link',
                                                    route('signature.show', [
                                                        'type' => 'chamber-g2',
                                                        'token' => $record->pic->fresh()->sign_token,
                                                    ])
                                                );
                                            }),

                                        ButtonAction::make('open')
                                            ->icon('heroicon-o-arrow-top-right-on-square')
                                            ->tooltip('Buka Link')
                                            ->hidden(fn($record) => blank($record->pic?->sign_token))
                                            ->alpineClickHandler(function ($record) {
                                                $url = route('signature.show', [
                                                    'type' => 'chamber-g2',
                                                    'token' => $record->pic->sign_token,
                                                ]);

                                                return "window.open('$url', '_blank')";
                                            }),

                                        ButtonAction::make('copy')
                                            ->icon('heroicon-o-clipboard')
                                            ->hidden(fn($record) => blank($record->pic?->sign_token))
                                            ->alpineClickHandler(
                                                fn($state) => "window.navigator.clipboard.writeText('$state'); \$tooltip('Copied to clipboard', { timeout: 1500 });"
                                            )
                                    ]),
                            ]),
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
                        ->url(fn($record) => route('pdf.MaintenanceChamberG2', ['record' => $record->id])),
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
            'index' => Pages\ListChamberG2S::route('/'),
            'create' => Pages\CreateChamberG2::route('/create'),
            'edit' => Pages\EditChamberG2::route('/{record}/edit'),
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
            ->required(
                fn($get) => filled($get('pic.checked_signature'))
                    && blank($get('pic.approved_signature'))
            )
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }
}
