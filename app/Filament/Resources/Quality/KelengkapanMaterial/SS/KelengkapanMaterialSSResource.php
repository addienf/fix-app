<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS;

use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\Pages;
use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\RelationManagers;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
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

class KelengkapanMaterialSSResource extends Resource
{
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
        $defaultParts = collect(config('kelengkapanSS.parts'))
            ->map(fn($part) => ['part' => $part])
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
                        //

                        self::selectInputSPK()
                            ->hiddenOn('edit'),

                        self::textInput('tipe', 'Type/Model')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('ref_document', 'Ref Document'),

                        self::textInput('no_order_temp', 'No Order')
                            ->columnSpanFull()
                            ->hiddenOn('edit')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                    ])->columns($isEdit ? 2 : 3),

                Section::make('Tabel Kelengkapan Material')
                    ->relationship('detail')
                    ->collapsible()
                    ->schema([

                        TableRepeater::make('details')
                            ->label('')
                            ->schema([

                                // TextInput::make('part')
                                //     ->label('Part'),
                                // ->extraAttributes([
                                //     'readonly' => true,
                                //     'style' => 'pointer-events: none;'
                                // ]),
                                TextInput::make('part')
                                    ->label('Part')
                                    ->readonly(fn($get) => filled($get('part')))
                                    ->extraAttributes(
                                        fn($get) => filled($get('part'))
                                            ? ['style' => 'pointer-events: none; background-color: #f3f4f6;']
                                            : []
                                    ),

                                self::buttonGroup('result', 'Result'),

                                Select::make('select')
                                    ->label('Keterangan')
                                    ->options([
                                        'ok' => 'OK',
                                        'h' => 'Hold',
                                        'r' => 'Repaired',
                                    ])
                                    ->required(),

                            ])
                            ->default($defaultParts)
                            ->columns(3)
                            ->addable(true)
                            ->reorderable(false)
                            ->deletable(false)
                            ->addActionLabel('Tambah Detail Kelengkapan Material'),
                    ]),

                Card::make('')
                    ->schema([

                        Textarea::make('note')
                            ->required()
                            ->label('Note')
                            ->columnSpanFull()

                    ]),

                Section::make('PIC')
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
                self::textColumn('spk.no_spk', 'NO SPK'),

                self::textColumn('tipe', 'Type/Model'),

                self::textColumn('ref_document', 'Ref Document'),

                TextColumn::make('status_penyelesaian')
                    ->label('Status')
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

    protected static function selectInputSPK(): Select
    {
        return
            Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            ->relationship(
                'spk',
                'no_spk',
                fn($query) => $query
                    ->whereHas('standarisasi', function ($query) {
                        $query->where('status_pemeriksaan', 'Diperiksa');
                    })->whereDoesntHave('kelengkapanSS')
            )
            ->native(false)
            ->searchable()
            ->placeholder('Pilin No SPK')
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                if (!$state) return;

                $spk = SPKMarketing::with('kelengkapanSS', 'jadwalProduksi')->find($state);

                if (!$spk) return;

                $no_order = $spk->no_order ?? '-';
                $tipe = $spk->jadwalProduksi->identifikasiProduks->first()?->tipe ?? '-';

                $set('no_order_temp', $no_order);
                $set('tipe', $tipe);
            });
    }

    protected static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                1 => 'Ya',
                0 => 'Tidak',
            ])
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->default('individual');
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Quality/KelengkapanMaterial/SS/Signatures');
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
