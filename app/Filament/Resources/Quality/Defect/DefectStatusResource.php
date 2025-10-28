<?php

namespace App\Filament\Resources\Quality\Defect;

use App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages;
use App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages\pdfDefectStatus;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
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
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class DefectStatusResource extends Resource
{
    protected static ?string $model = DefectStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Defect Status';
    protected static ?string $pluralLabel = 'Defect Status';
    protected static ?string $modelLabel = 'Defect Status';
    protected static ?string $slug = 'quality/defect-status';

    public static function getNavigationBadge(): ?string
    {
        $count = DefectStatus::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $isCreate = $form->getOperation() === 'create';
        $isEdit = $form->getOperation() === 'edit';
        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                Section::make('Chamber Identification')
                    ->collapsible()
                    ->schema([
                        self::pilihModel()
                            ->hiddenOn('edit'),

                        self::pilihId()
                            ->hiddenOn('edit'),

                        self::textInput('tipe', 'Type/Model')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('no_surat', 'No Surat'),

                        self::textInput('volume', 'Volume'),

                        self::textInput('serial_number', 'S/N'),

                        Hidden::make('spk_marketing_id'),

                    ])->columns($isEdit ? 3 : 5),


                Section::make('Tabel Checklist Ditolak')
                    ->collapsed($isEdit ? true : false)
                    ->collapsible()
                    ->schema([
                        Repeater::make('details')
                            // ->relationship('details')
                            ->label('')
                            ->schema([
                                self::spesifikasiDitolak(),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->columnSpanFull()
                            ->when(
                                $isCreate,
                                fn($component) => $component->relationship('details'),
                            )
                    ])
                    ->hiddenOn('create'),

                Section::make('Tabel Checklist Revisi')
                    ->collapsed($isEdit ? true : false)
                    ->collapsible()
                    ->schema([
                        Repeater::make('details')
                            ->relationship('details')
                            ->label('')
                            ->schema([

                                self::spesifikasiRevisi(),

                                Hidden::make('spesifikasi_ditolak')
                                    ->disabledOn('edit'),

                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->columnSpanFull()
                    ]),

                Fieldset::make('')
                    ->schema([
                        Textarea::make('note')
                            ->required()
                            ->label('Note')
                            ->columnSpanFull()
                    ]),

                FileUpload::make('file_upload')
                    ->label('File Pendukung')
                    ->directory('Quality/DefectStatus/Files')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.'),

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

                TextColumn::make('tipe_sumber')
                    ->label('Jenis')
                    ->formatStateUsing(
                        fn($state) =>
                        $state === 'electrical' ? 'Pengecekan Material Electrical' : 'Pengecekan Stainless Steel'
                    ),

                self::textColumn('tipe', 'Tipe'),

                self::textColumn('volume', 'Volume'),

                self::textColumn('serial_number', 'S/N'),

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
                        // ->url(fn($record) => route('pdf.defectStatus')),
                        ->url(fn($record) => route('pdf.defectStatus', ['record' => $record->id])),
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
            'index' => Pages\ListDefectStatuses::route('/'),
            'create' => Pages\CreateDefectStatus::route('/create'),
            'edit' => Pages\EditDefectStatus::route('/{record}/edit'),
            'pdfDefectStatus' => pdfDefectStatus::route('/{record}/pdfDefectStatus')
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Quality/DefectStatus/Signatures');
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

    protected static function pilihModel(): Select
    {
        return
            Select::make('tipe_sumber')
            ->label('Jenis Pengecekan')
            ->options([
                'electrical' => 'Pengecekan Electrical',
                'stainless_steel' => 'Pengecekan Stainless Steel',
            ])
            ->reactive()
            ->required()
            ->disabledOn('edit');
    }

    protected static function pilihId(): Select
    {
        return
            Select::make('sumber_id')
            ->label('Data Pengecekan')
            // ->options(function (callable $get) {
            //     $tipe = $get('tipe_sumber');

            //     return match ($tipe) {
            //         'electrical' => PengecekanMaterialElectrical::whereDoesntHave('defectStatus')->get()
            //             ->mapWithKeys(fn($item) => [$item->id => $item->spk->no_spk]),

            //         'stainless_steel' => PengecekanMaterialSS::whereDoesntHave('defectStatus')->get()
            //             ->mapWithKeys(fn($item) => [$item->id => $item->spk->no_spk]),

            //         default => [],
            //     };
            // })
            ->options(function (callable $get) {
                $tipe = $get('tipe_sumber');

                return match ($tipe) {
                    'electrical' => PengecekanMaterialElectrical::whereDoesntHave('defectStatus')
                        ->get()
                        ->filter(
                            fn($item) => collect($item->detail->details ?? [])
                                ->contains(function ($d) {
                                    $hasMainPartNo = ($d['mainPart_result'] ?? '') === '0';
                                    $hasPartNo = collect($d['parts'] ?? [])->contains(fn($p) => ($p['result'] ?? '') === '0');
                                    return $hasMainPartNo || $hasPartNo;
                                })
                        )
                        ->mapWithKeys(fn($item) => [$item->id => $item->spk->no_spk]),

                    'stainless_steel' => PengecekanMaterialSS::whereDoesntHave('defectStatus')
                        ->get()
                        ->filter(
                            fn($item) => collect($item->detail->details ?? [])
                                ->contains(function ($d) {
                                    $hasMainPartNo = ($d['mainPart_result'] ?? '') === '0';
                                    $hasPartNo = collect($d['parts'] ?? [])->contains(fn($p) => ($p['result'] ?? '') === '0');
                                    return $hasMainPartNo || $hasPartNo;
                                })
                        )
                        ->mapWithKeys(fn($item) => [$item->id => $item->spk->no_spk]),

                    default => [],
                };
            })
            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                // $sumberId  = $get('sumber_id');
                $tipe = $get('tipe_sumber');

                $model = match ($tipe) {
                    'electrical' => PengecekanMaterialElectrical::find($state),
                    'stainless_steel' => PengecekanMaterialSS::find($state),
                    default => null,
                };

                if (!$model || !is_array($model->detail->details)) {
                    $set('details', []);
                    return;
                }

                $no_spk = $model->spk_marketing_id;

                $spk = SPKMarketing::with('jadwalProduksi')->find($no_spk);

                $tipeProduk = $spk?->jadwalProduksi?->identifikasiProduks->first()->tipe;

                $ditolak = collect($model->detail->details)
                    ->map(function ($item) {
                        $filteredParts = collect($item['parts'])
                            ->filter(fn($part) => $part['result'] === "0")
                            ->map(fn($part) => [
                                'part' => $part['part'] ?? '',
                                'result' => $part['result'],
                                'status' => $part['status'],
                            ])
                            ->values()
                            ->toArray();


                        return [
                            'mainPart' => $item['mainPart'] ?? '',
                            'mainPart_result' => $item['mainPart_result'] ?? '',
                            'mainPart_status' => $item['mainPart_status'] ?? '',
                            'parts' => $filteredParts,
                        ];
                    })
                    ->filter(fn($item) => count($item['parts']) > 0)
                    ->values()
                    ->toArray();

                $set('details', [
                    [
                        'spesifikasi_ditolak' => $ditolak,
                        'spesifikasi_revisi' => $ditolak,
                    ]
                ]);

                $set('spk_marketing_id', $no_spk);
                $set('tipe', $tipeProduk);
            })
            ->reactive()
            ->required()
            ->visible(fn(callable $get) => filled($get('tipe_sumber')))
            ->disabledOn('edit');
    }

    protected static function spesifikasiDitolak(): Repeater
    {
        return
            Repeater::make('spesifikasi_ditolak')
            ->label('')
            ->schema([

                Grid::make(3)
                    ->schema([
                        TextInput::make('mainPart')
                            ->label('Main Parts')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        ButtonGroup::make('mainPart_result')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row'),

                        Select::make('mainPart_status')
                            ->label('Status')
                            ->options([
                                'ok' => 'OK',
                                'h' => 'Hold',
                                'r' => 'Repaired',
                            ])
                            ->required()
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
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
                            ->gridDirection('row'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'ok' => 'OK',
                                'h' => 'Hold',
                                'r' => 'Repaired',
                            ])
                            ->required()
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false),

            ])
            ->addable(false)
            ->deletable(false)
            ->reorderable(false)
            ->columnSpanFull();
    }

    protected static function spesifikasiRevisi(): Repeater
    {
        return
            Repeater::make('spesifikasi_revisi')
            ->label('')
            ->schema([

                Grid::make(3)
                    ->schema([
                        TextInput::make('mainPart')
                            ->label('Main Parts')
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
                            ->gridDirection('row'),

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
                            ->gridDirection('row'),

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
            ->columnSpanFull();
    }
}
