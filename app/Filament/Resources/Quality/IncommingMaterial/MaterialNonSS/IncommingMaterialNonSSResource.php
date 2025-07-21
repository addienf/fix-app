<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;
use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\RelationManagers;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
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

class IncommingMaterialNonSSResource extends Resource
{
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

        $lastValue = IncommingMaterialNonSS::latest('no_po')->value('no_po');

        $defaultParts = collect(config('incommingMaterialNonSS.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        // $rows = config('summaryNonSS.rows');

        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([

                        self::selectInput('permintaan_pembelian_id', 'Permintaan Pembelian', 'permintaanPembelian', 'id')
                            ->placeholder('Pilih Nomor Permintaan Pembelian')
                            ->hiddenOn('edit')
                            ->required(),

                        self::textInput('no_po', 'No. PO')
                            ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                            ->hint('Format: XXX/QKS/WBB/PERMINTAAN/MM/YY'),

                        self::textInput('supplier', 'Supplier'),

                    ]),

                Section::make('Tabel Kelengkapan Material')
                    ->collapsible()
                    ->relationship('detail')
                    ->schema([
                        TableRepeater::make('details')
                            ->label('')
                            ->schema([
                                self::textInput('part', 'Description')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                ButtonGroup::make('result')
                                    ->options([
                                        '1' => 'Pass',
                                        '0' => 'Fail',
                                    ])
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row')
                                    ->default('individual'),

                                self::textInput('remark', 'Remark')

                            ])
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false)
                            ->default($defaultParts)
                            ->columns(3),

                        TableRepeater::make('details_tambahan')
                            ->label('')
                            ->schema([

                                self::textInput('part', 'Description'),

                                ButtonGroup::make('result')
                                    ->options([
                                        '1' => 'Pass',
                                        '0' => 'Fail',
                                    ])
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row')
                                    ->default('individual'),

                                self::textInput('remark', 'Remark')

                            ])
                            ->columnSpanFull()
                            ->default([])
                            ->reorderable(false)
                            ->addActionLabel('Tambah Checklist')
                            ->columns(3)

                    ]),

                Section::make('Summary')
                    ->relationship('summary')
                    ->collapsible()
                    ->schema([

                        Repeater::make('summary')
                            ->label('')
                            ->schema([

                                Grid::make(4)
                                    ->schema([
                                        Placeholder::make('summary_header')
                                            ->label('')
                                            ->content('Summary')
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),

                                        Placeholder::make('critical_header')
                                            ->label('')
                                            ->content('Critical')
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),

                                        Placeholder::make('major_header')
                                            ->label('')
                                            ->content('Major')
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),

                                        Placeholder::make('minor_header')
                                            ->label('')
                                            ->content('Minor')
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),
                                    ]),

                                Grid::make(4)
                                    ->schema([

                                        Placeholder::make("summary_labels")
                                            ->label('')
                                            ->content("Total Received Quantity")
                                            ->columns(1)
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),

                                        self::textInput('critical_1', '')
                                            ->required(false),

                                        self::textInput('major_1', '')
                                            ->required(false),


                                        self::textInput('minor_1', '')
                                            ->required(false),


                                        Placeholder::make("summary_labels")
                                            ->label('')
                                            ->content("Return Quantity to Supplier")
                                            ->columns(1)
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),

                                        self::textInput('critical_2', '')
                                            ->required(false),


                                        self::textInput('major_2', '')
                                            ->required(false),


                                        self::textInput('minor_2', '')
                                            ->required(false),


                                        Placeholder::make("summary_labels")
                                            ->label('')
                                            ->content("Total Rejected Quantity")
                                            ->columns(1)
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),

                                        self::textInput('critical_3', '')
                                            ->required(false),


                                        self::textInput('major_3', '')
                                            ->required(false),


                                        self::textInput('minor_3', '')
                                            ->required(false),

                                    ]),

                                Grid::make(4)
                                    ->schema([

                                        Placeholder::make("summary_labels")
                                            ->label('')
                                            ->content("Total Acceptable Quantity")
                                            ->columns(1)
                                            ->extraAttributes(['class' => 'font-semibold text-center bg-gray-50 border py-2 rounded-md']),

                                        self::textInput('total_acceptable_quantity', '')
                                            ->required(false)
                                            ->columnSpan(3),

                                    ]),

                            ])
                            ->reorderable(false)
                            ->deletable(false)
                            ->addable(false),
                    ]),

                Section::make('Nomor Batch')
                    ->collapsible()
                    ->schema([

                        self::textInput('batch_no', 'Batch No')

                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('checked_name')
                                            ->default(fn() => auth()->id()),

                                        self::textInput('checked_name_placeholder', 'Checked By')
                                            ->default(fn() => auth()->user()?->name)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('checked_name', 'Checked By'),

                                        self::signatureInput('checked_signature', ''),

                                        self::datePicker('checked_date', '')
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
                self::textColumn('no_po', 'No PO'),

                self::textColumn('supplier', 'Supplier'),

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

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function textArea(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required();
    }

    protected static function selectInput(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
                ->relationship($relation, $title)
                ->options(function () {
                    return
                        PermintaanPembelian::with('permintaanBahanWBB')
                            ->whereDoesntHave('materialNonSS')
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->id => $item->permintaanBahanWBB->no_surat ?? 'Tanpa No Surat'];
                            });
                })
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
                    $path = SignatureUploader::handle($state, 'ttd_', 'Quality/IncommingMaterial/NonSS/Signatures');
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