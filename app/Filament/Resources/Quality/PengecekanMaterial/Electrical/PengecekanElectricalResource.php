<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages\pdfPengecekanElectrical;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\RelationManagers;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Card;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class PengecekanElectricalResource extends Resource
{
    protected static ?string $model = PengecekanMaterialElectrical::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 17;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Pengecekan Material Electrical';
    protected static ?string $pluralLabel = 'Pengecekan Material Electrical';
    protected static ?string $modelLabel = 'Pengecekan Material Electrical';
    protected static ?string $slug = 'quality/pengecekan-material-electrical';
    public static function getNavigationBadge(): ?string
    {
        $count = PengecekanMaterialElectrical::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }
    public static function form(Form $form): Form
    {
        $defaultParts = collect(config('pengecekanElectrical'))
            ->map(function ($group) {
                return [
                    'mainPart' => $group['mainPart'],
                    'parts' => collect($group['parts'])
                        ->map(fn($part) => ['part' => $part])
                        ->toArray(),
                ];
            })
            ->toArray();

        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                Section::make('Chamber Identification')
                    ->collapsible()
                    ->schema([

                        Grid::make($isEdit ? 2 : 3)
                            ->schema([

                                //
                                self::selectInputSPK()
                                    ->hiddenOn('edit')
                                    ->placeholder('Pilih No SPK'),

                                self::textInput('tipe', 'Type/Model')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('volume', 'Volume')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                            ]),

                    ]),

                Section::make('Tabel Kelengkapan Material')
                    ->collapsible()
                    ->relationship('detail')
                    ->schema([

                        Repeater::make('details')
                            ->default($defaultParts)
                            ->label('')
                            ->schema([

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('mainPart')
                                            ->label('Main Part')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        ButtonGroup::make('mainPart_result')
                                            ->label('Result')
                                            ->options([
                                                1 => 'Yes',
                                                0 => 'No',
                                            ])
                                            ->onColor('primary')
                                            ->offColor('gray')
                                            ->gridDirection('row')
                                            ->default('individual'),

                                        Select::make('mainPart_status')
                                            ->label('Status')
                                            ->options([
                                                'ok' => 'OK',
                                                'h' => 'Hold',
                                                'r' => 'Repaired',
                                            ])
                                            ->required(),
                                    ]),

                                TableRepeater::make('parts')
                                    ->label('')
                                    ->schema([

                                        TextInput::make('part')
                                            ->label('Part')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        ButtonGroup::make('result')
                                            ->options([
                                                1 => 'Yes',
                                                0 => 'No',
                                            ])
                                            ->onColor('primary')
                                            ->offColor('gray')
                                            ->gridDirection('row')
                                            ->default('individual'),

                                        Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'ok' => 'OK',
                                                'h' => 'Hold',
                                                'r' => 'Repaired',
                                            ])
                                            ->required(),

                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false),

                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)

                    ]),

                Card::make('')
                    ->schema([

                        Textarea::make('note')
                            ->required()
                            ->label('Note')
                            ->columnSpanFull()

                    ]),

                Section::make('Detail PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('inspected_name')
                                            ->default(fn() => auth()->id()),

                                        self::textInput('inspected_name_placeholder', 'Inspected By')
                                            ->default(fn() => auth()->user()?->name)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('inspected_name', 'Inspected By'),

                                        self::signatureInput('inspected_signature', ''),

                                        self::datePicker('inspected_date', '')
                                            ->default(now())
                                            ->required(),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('accepted_name')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        self::textInput('accepted_name_placeholder', 'Accepted By')
                                            ->default(fn() => auth()->user()?->name)
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->required(false)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('accepted_name', 'Accepted By'),

                                        self::signatureInput('accepted_signature', ''),

                                        self::datePicker('accepted_date', '')
                                            ->required(),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || filled($record?->accepted_signature)
                                    ),

                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('approved_name')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        self::textInput('approved_name_placeholder', 'Approved By')
                                            ->default(fn() => auth()->user()?->name)
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->required(false)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('approved_name', 'Approved By'),

                                        self::signatureInput('approved_signature', ''),

                                        self::datePicker('approved_date', '')
                                            ->required(),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
                                    ),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'No SPK'),

                self::textColumn('tipe', 'Type/Model'),

                self::textColumn('volume', 'Volume'),

                TextColumn::make('status_penyelesaian')
                    ->label('Status Penyelesaian')
                    ->badge()
                    ->color(function ($record) {
                        $penyelesaian = $record->status_penyelesaian;
                        $persetujuan = $record->status_persetujuan;

                        if ($penyelesaian === 'Disetujui') {
                            return 'success';
                        }

                        if ($penyelesaian !== 'Diterima' && $persetujuan !== 'Disetujui') {
                            return 'danger';
                        }

                        return 'warning';
                    })
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
                        ->visible(fn($record) => $record->status_penyelesaian === 'Disetujui')
                        ->url(fn($record) => route('pdf.pengecekanElectrical', ['record' => $record->id])),
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
            'index' => Pages\ListPengecekanElectricals::route('/'),
            'create' => Pages\CreatePengecekanElectrical::route('/create'),
            'edit' => Pages\EditPengecekanElectrical::route('/{record}/edit'),
            'pdfPengecekanElectrical' => pdfPengecekanElectrical::route('/{record}/pdfPengecekanElectrical')
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function selectInput(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
            ->relationship($relation, $title)
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
    }

    protected static function selectInputOptions(string $fieldName, string $label, string $config): Select
    {
        return
            Select::make($fieldName)
            ->options(config($config))
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
    }

    protected static function selectInputSPK(): Select
    {
        return
            Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            ->relationship(
                'spk',
                'no_spk',
                fn($query) => $query
                    ->whereHas('spkQC', function ($query) {
                        $query->where('status_penerimaan', 'Diterima');
                    })->whereDoesntHave('pengecekanElectrical')
            )
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                if (!$state) return;

                $spk = SPKMarketing::with('jadwalProduksi')->find($state);

                if (!$spk) return;

                $tipe = $spk?->jadwalProduksi?->details->first()->tipe;
                $volume = $spk?->jadwalProduksi?->details->first()->volume;

                $set('tipe', $tipe);
                $set('volume', $volume);
            });
    }

    protected static function datePicker(string $fieldName, string $label): DatePicker
    {
        return
            DatePicker::make($fieldName)
            ->label($label)
            ->displayFormat('M d Y')
            ->seconds(false);
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Quality/PengecekanMaterial/Electrical/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }

    protected static function textColumn(string $fieldName, string $label): TextColumn
    {
        return
            TextColumn::make($fieldName)
            ->label($label)
            ->searchable()
            ->sortable();
    }
}
