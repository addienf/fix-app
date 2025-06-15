<?php

namespace App\Filament\Resources\Quality\Pengecekan;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;
use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\RelationManagers;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
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

class PengecekanPerformaResource extends Resource
{
    protected static ?string $model = PengecekanPerforma::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 19;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Pengecekan Performa';
    protected static ?string $pluralLabel = 'Pengecekan Performa';
    protected static ?string $modelLabel = 'Pengecekan Performa';
    protected static ?string $slug = 'quality/pengecekan-performa';

    public static function getNavigationBadge(): ?string
    {
        $count = PengecekanPerforma::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $defaultParts = collect(config('pengecekanPerforma'))
            ->map(function ($group) {
                return [
                    'mainPart' => $group['mainPart'],
                    'parts' => collect($group['parts'])
                        ->map(fn($part) => ['part' => $part])
                        ->toArray(),
                ];
            })
            ->toArray();

        return $form
            ->schema([
                //

                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                Section::make('Chamber Identification')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                self::selectInputSPK()
                                    ->placeholder('Pilin No SPK'),

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

                                self::textInput('serial_number', 'S/N'),

                            ]),
                    ]),

                Section::make('Tabel Kelengkapan Material')
                    ->collapsible()
                    ->relationship('detail')
                    ->schema([

                        Repeater::make('details')
                            ->default($defaultParts)
                            ->schema([

                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
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
                                                '1' => 'Yes',
                                                '0' => 'No',
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

                                        self::textInput('inspected_name', 'Inspected By'),

                                        self::signatureInput('inspected_signature', ''),

                                        self::datePicker('inspected_date', '')
                                            ->required(),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('accepted_name', 'Accepted By'),

                                        self::signatureInput('accepted_signature', ''),

                                        self::datePicker('accepted_date', '')
                                            ->required(),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || filled($record?->accepted_signature)
                                    ),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('approved_name', 'Approved By'),

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

                self::textColumn('serial_number', 'S/N'),

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

                ImageColumn::make('pic.inspected_signature')
                    ->width(150)
                    ->label('Inspected')
                    ->height(75),

                ImageColumn::make('pic.accepted_signature')
                    ->width(150)
                    ->label('Accepted')
                    ->height(75),

                ImageColumn::make('pic.approved_signature')
                    ->width(150)
                    ->label('Approved')
                    ->height(75),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('pdf_view')
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penyelesaian === 'Disetujui')
                        ->url(fn($record) => self::getUrl('pdfPengecekanPerforma', ['record' => $record->id])),
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
            'index' => Pages\ListPengecekanPerformas::route('/'),
            'create' => Pages\CreatePengecekanPerforma::route('/create'),
            'edit' => Pages\EditPengecekanPerforma::route('/{record}/edit'),
            'pdfPengecekanPerforma' => Pages\pdfPengecekanPerforma::route('/{record}/pdfPengecekanPerforma')
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
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
                    ->whereHas('produkJadi', function ($query) {
                        $query->where('status_penerimaan', 'Diterima');
                    })->whereDoesntHave('pengecekanPerforma')
            )
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spk = SPKMarketing::with('jadwalProduksi')->find($state);

                if (!$spk) return;

                $tipe = $spk?->jadwalProduksi?->details->first()->tipe;
                $volume = $spk?->jadwalProduksi?->details->first()->volume;

                $set('tipe', $tipe);
                $set('volume', $volume);
            })
        ;
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Quality/PengecekanMaterial/SS/Signatures');
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
