<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages\pdfPengecekanElectrical;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\Traits\ChamberIdentification;
use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\Traits\TabelKelengkapanMaterial;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengecekanElectricalResource extends Resource
{
    use ChamberIdentification, TabelKelengkapanMaterial, HasSignature;
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
        // $defaultParts = collect(config('pengecekanElectrical'))
        //     ->map(function ($group) {
        //         return [
        //             'mainPart' => $group['mainPart'],
        //             'parts' => collect($group['parts'])
        //                 ->map(fn($part) => ['part' => $part])
        //                 ->toArray(),
        //         ];
        //     })
        //     ->toArray();

        // $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                self::getChamberIdentificationSection($form),

                self::getTabelKelengkapanMaterialSection(),

                self::getNote(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'inspected',
                            'role' => 'Inspected by',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'accepted',
                            'role' => 'Accepted by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->accepted_signature)
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Approved by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/PengecekanMaterial/Electrical/Signatures'
                ),

                // Section::make('Chamber Identification')
                //     ->collapsible()
                //     ->schema([

                //         Grid::make($isEdit ? 2 : 3)
                //             ->schema([

                //                 //
                //                 self::selectInputSPK()
                //                     ->hiddenOn('edit')
                //                     ->placeholder('Pilih No SPK'),

                //                 self::textInput('tipe', 'Type/Model')
                //                     ->extraAttributes([
                //                         'readonly' => true,
                //                         'style' => 'pointer-events: none;'
                //                     ]),

                //                 self::textInput('volume', 'Volume')
                //                     ->extraAttributes([
                //                         'readonly' => true,
                //                         'style' => 'pointer-events: none;'
                //                     ]),

                //             ]),

                //     ]),

                // Section::make('Tabel Kelengkapan Material')
                //     ->collapsible()
                //     ->relationship('detail')
                //     ->schema([

                //         Repeater::make('details')
                //             ->default($defaultParts)
                //             ->label('')
                //             ->schema([

                //                 Grid::make(3)
                //                     ->schema([
                //                         TextInput::make('mainPart')
                //                             ->label('Main Part')
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),

                //                         ButtonGroup::make('mainPart_result')
                //                             ->label('Result')
                //                             ->options([
                //                                 1 => 'Yes',
                //                                 0 => 'No',
                //                             ])
                //                             ->onColor('primary')
                //                             ->offColor('gray')
                //                             ->gridDirection('row')
                //                             ->default('individual'),

                //                         Select::make('mainPart_status')
                //                             ->label('Status')
                //                             ->options([
                //                                 'ok' => 'OK',
                //                                 'h' => 'Hold',
                //                                 'r' => 'Repaired',
                //                             ])
                //                             ->required(),
                //                     ]),

                //                 TableRepeater::make('parts')
                //                     ->label('')
                //                     ->schema([

                //                         TextInput::make('part')
                //                             ->label('Part')
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),

                //                         ButtonGroup::make('result')
                //                             ->options([
                //                                 1 => 'Yes',
                //                                 0 => 'No',
                //                             ])
                //                             ->onColor('primary')
                //                             ->offColor('gray')
                //                             ->gridDirection('row')
                //                             ->default('individual'),

                //                         Select::make('status')
                //                             ->label('Status')
                //                             ->options([
                //                                 'ok' => 'OK',
                //                                 'h' => 'Hold',
                //                                 'r' => 'Repaired',
                //                             ])
                //                             ->required(),

                //                     ])
                //                     ->addable(false)
                //                     ->deletable(false)
                //                     ->reorderable(false),

                //             ])
                //             ->addable(false)
                //             ->deletable(false)
                //             ->reorderable(false)

                //     ]),

                // Card::make('')
                //     ->schema([

                //         Textarea::make('note')
                //             ->required()
                //             ->label('Note')
                //             ->columnSpanFull()

                //     ]),

                // Section::make('Detail PIC')
                //     ->collapsible()
                //     ->relationship('pic')
                //     ->schema([
                //         Grid::make(3)
                //             ->schema([
                //                 Grid::make(1)
                //                     ->schema([

                //                         Hidden::make('inspected_name')
                //                             ->default(fn() => auth()->id()),

                //                         self::textInput('inspected_name_placeholder', 'Inspected By')
                //                             ->default(fn() => auth()->user()?->name)
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),

                //                         // self::textInput('inspected_name', 'Inspected By'),

                //                         self::signatureInput('inspected_signature', ''),

                //                         self::datePicker('inspected_date', '')
                //                             ->default(now())
                //                             ->required(),

                //                     ])->hiddenOn(operations: 'edit'),

                //                 Grid::make(1)
                //                     ->schema([

                //                         Hidden::make('accepted_name')
                //                             ->default(fn() => auth()->id())
                //                             ->dehydrated(true)
                //                             ->afterStateHydrated(function ($component) {
                //                                 $component->state(auth()->id());
                //                             }),

                //                         self::textInput('accepted_name_placeholder', 'Accepted By')
                //                             ->default(fn() => auth()->user()?->name)
                //                             ->placeholder(fn() => auth()->user()?->name)
                //                             ->required(false)
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),

                //                         // self::textInput('accepted_name', 'Accepted By'),

                //                         self::signatureInput('accepted_signature', ''),

                //                         self::datePicker('accepted_date', '')
                //                             ->required(),

                //                     ])->hidden(
                //                         fn($operation, $record) =>
                //                         $operation === 'create' || filled($record?->accepted_signature)
                //                     ),

                //                 Grid::make(1)
                //                     ->schema([

                //                         Hidden::make('approved_name')
                //                             ->default(fn() => auth()->id())
                //                             ->dehydrated(true)
                //                             ->afterStateHydrated(function ($component) {
                //                                 $component->state(auth()->id());
                //                             }),

                //                         self::textInput('approved_name_placeholder', 'Approved By')
                //                             ->default(fn() => auth()->user()?->name)
                //                             ->placeholder(fn() => auth()->user()?->name)
                //                             ->required(false)
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),

                //                         // self::textInput('approved_name', 'Approved By'),

                //                         self::signatureInput('approved_signature', ''),

                //                         self::datePicker('approved_date', '')
                //                             ->required(),

                //                     ])->hidden(
                //                         fn($operation, $record) =>
                //                         $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
                //                     ),
                //             ]),
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk.no_spk')
                    ->label('No SPK Marketing'),

                TextColumn::make('penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks.no_seri')
                    ->label('No Seri'),

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

    // protected static function textInput(string $fieldName, string $label): TextInput
    // {
    //     return TextInput::make($fieldName)
    //         ->label($label)
    //         ->required()
    //         ->maxLength(255);
    // }

    // protected static function selectInput(string $fieldName, string $label, string $relation, string $title): Select
    // {
    //     return
    //         Select::make($fieldName)
    //         ->relationship($relation, $title)
    //         ->label($label)
    //         ->native(false)
    //         ->searchable()
    //         ->preload()
    //         ->required()
    //         ->reactive();
    // }

    // protected static function selectInputOptions(string $fieldName, string $label, string $config): Select
    // {
    //     return
    //         Select::make($fieldName)
    //         ->options(config($config))
    //         ->label($label)
    //         ->native(false)
    //         ->searchable()
    //         ->preload()
    //         ->required()
    //         ->reactive();
    // }

    // protected static function selectInputSPK(): Select
    // {
    //     return
    //         Select::make('spk_marketing_id')
    //         ->label('Nomor SPK')
    //         ->relationship(
    //             'spk',
    //             'no_spk',
    //             fn($query) => $query
    //                 ->whereHas('spkQC', function ($query) {
    //                     $query->where('status_penerimaan', 'Diterima');
    //                 })->whereDoesntHave('pengecekanElectrical')
    //         )
    //         ->native(false)
    //         ->searchable()
    //         ->preload()
    //         ->required()
    //         ->reactive()
    //         ->afterStateUpdated(function ($state, callable $set, callable $get) {
    //             if (!$state) return;

    //             $spk = SPKMarketing::with('jadwalProduksi', 'defect')->find($state);

    //             if (!$spk) return;

    //             $tipe = $spk?->jadwalProduksi?->identifikasiProduks->first()->tipe;
    //             $volume = $spk?->defect?->volume;

    //             $set('tipe', $tipe);
    //             $set('volume', $volume);
    //         });
    // }

    // protected static function datePicker(string $fieldName, string $label): DatePicker
    // {
    //     return
    //         DatePicker::make($fieldName)
    //         ->label($label)
    //         ->displayFormat('M d Y')
    //         ->seconds(false);
    // }

    // protected static function signatureInput(string $fieldName, string $labelName): SignaturePad
    // {
    //     return
    //         SignaturePad::make($fieldName)
    //         ->label($labelName)
    //         ->exportPenColor('#0118D8')
    //         ->helperText('*Harap Tandatangan di tengah area yang disediakan.')
    //         ->afterStateUpdated(function ($state, $set) use ($fieldName) {
    //             if (blank($state))
    //                 return;
    //             $path = SignatureUploader::handle($state, 'ttd_', 'Quality/PengecekanMaterial/Electrical/Signatures');
    //             if ($path) {
    //                 $set($fieldName, $path);
    //             }
    //         });
    // }

    // protected static function textColumn(string $fieldName, string $label): TextColumn
    // {
    //     return
    //         TextColumn::make($fieldName)
    //         ->label($label)
    //         ->searchable()
    //         ->sortable();
    // }
}
