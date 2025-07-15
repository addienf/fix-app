<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2;

use App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2Resource\Pages;
use App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2Resource\RelationManagers;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\SPK\SPKService;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class ChamberWalkinG2Resource extends Resource
{
    protected static ?string $model = ChamberWalkinG2::class;
    protected static ?int $navigationSort = 28;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Walk-in Chamber G2';
    protected static ?string $pluralLabel = 'Walk-in Chamber G2';
    protected static ?string $modelLabel = 'Walk-in Chamber G2';
    protected static ?string $slug = 'engineering/walkin-chamber-g2';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationBadge(): ?string
    {
        $count = ChamberWalkinG2::where('status_penyetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $defaultParts = collect(config('walkinChamberG2'))
            ->map(function ($group) {
                return [
                    'mainPart' => $group['mainPart'],
                    'parts' => collect($group['parts'])
                        ->map(fn($part) => ['part' => $part])
                        ->toArray(),
                ];
            })
            ->toArray();
        $lastValue = ChamberWalkinG2::latest('tag_no')->value('tag_no');
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penyetujuan')
                    ->default('Belum Disetujui'),

                Fieldset::make('Informasi')
                    ->label('')
                    ->schema([
                        self::textInput('tag_no', 'WTC Name/TAG No')
                            ->hint('Format: TAG No.')
                            ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                            // ->hiddenOn('edit')
                            ->unique(ignoreRecord: true),

                        Select::make('spk_service_id')
                            ->label('Nomor SPK Service')
                            ->options(function () {
                                return SPKService::where('status_penyelesaian', 'Selesai')
                                    ->whereDoesntHave('walkinG2')
                                    ->pluck('no_spk_service', 'id');
                            })
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->hiddenOn(operations: 'edit'),
                    ])
                    ->columns($isEdit ? 1 : 2),

                Section::make('Tabel Checklist')
                    ->collapsible()
                    ->relationship('detail')
                    ->schema([

                        Repeater::make('checklist')
                            ->default($defaultParts)
                            ->label('')
                            ->schema([

                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->hidden(fn(callable $get) => blank($get('mainPart')))
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                Repeater::make('parts')
                                    ->label('')
                                    ->schema([

                                        TextInput::make('part')
                                            ->columnSpan(3)
                                            ->required(),

                                        TextInput::make('before')
                                            ->columnSpan(1)
                                            ->required(),

                                        TextInput::make('after')
                                            ->columnSpan(1)
                                            ->required(),

                                        Select::make('accepted')
                                            ->options([
                                                'yes' => 'Yes',
                                                'no' => 'No',
                                                'na' => 'NA',
                                            ])
                                            ->columnSpan(1)
                                            ->required(),

                                        Select::make('remark')
                                            ->options([
                                                'ok' => 'OK',
                                                'h' => 'Hold',
                                                'r' => 'Repaired',
                                            ])
                                            ->columnSpan(1)
                                            ->required(),

                                    ])
                                    ->columns(7)
                                    ->defaultItems(1)
                                    ->addable(true)
                                    ->deletable(false)
                                    ->reorderable(false),

                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)

                    ]),

                Fieldset::make('Remarks')
                    ->label('')
                    ->schema([
                        Textarea::make('remarks')
                            ->required()
                            ->columnSpanFull()
                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([
                                        self::textInput('checked_name', 'Checked By'),
                                        self::signatureInput('checked_signature', ''),
                                        DatePicker::make('checked_date')
                                            ->label('')
                                            ->required()
                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([
                                        self::textInput('approved_name', 'Approved By'),
                                        self::signatureInput('approved_signature', ''),
                                        DatePicker::make('approved_date')
                                            ->label('')
                                            ->required()
                                    ])->hiddenOn(operations: 'create'),

                            ]),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('tag_no')
                    ->label('WTC Name/TAG No'),

                TextColumn::make('status_penyetujuan')
                    ->label('Status')
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
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penyetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.walkInChamberG2', ['record' => $record->id])),
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
            'index' => Pages\ListChamberWalkinG2S::route('/'),
            'create' => Pages\CreateChamberWalkinG2::route('/create'),
            'edit' => Pages\EditChamberWalkinG2::route('/{record}/edit'),
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function signatureInput(string $fieldName, string $labelName): SignaturePad
    {
        return
            SignaturePad::make($fieldName)
            ->label($labelName)
            ->exportPenColor('#0118D8')
            ->helperText('*Harap Tandatangan di tengah area yang disediakan.')
            ->afterStateUpdated(function ($state, $set) use ($fieldName) {
                if (blank($state))
                    return;
                $path = SignatureUploader::handle($state, 'ttd_', 'Engineering/Maintenance/WalkinChamberG2/Signature');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
