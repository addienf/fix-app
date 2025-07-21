<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\Pages;
use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\RelationManagers;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class IncommingMaterialSSResource extends Resource
{
    protected static ?string $model = IncommingMaterialSS::class;
    protected static ?string $slug = 'quality/incoming-material-stainless-steel';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 16;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Incoming Material SS';
    protected static ?string $pluralLabel = 'Incoming Material Stainless Steel';
    protected static ?string $modelLabel = 'Incoming Material Stainless Steel';

    public static function getNavigationBadge(): ?string
    {
        $count = IncommingMaterialSS::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $lastValue = IncommingMaterialSS::latest('no_po')->value('no_po');

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

                Section::make('Checklist Table')
                    ->relationship('detail')
                    ->schema([

                        Repeater::make('checklists')
                            ->label('')
                            ->schema([

                                Textarea::make('procedures')
                                    ->disabled()
                                    ->rows(6)
                                    ->label('Procedures')
                                    ->formatStateUsing(fn($state) => $state ??
                                        "- Wipe of the dust, dirt, oil, and water on the surface of material\n- Make a mark on the upper, middle and buttom side of the material surface\n- Make a mark on the upper, middle and buttom side of the material surface"),

                                Textarea::make('expected_result')
                                    ->disabled()
                                    ->rows(6)
                                    ->label('Expected Result')
                                    ->formatStateUsing(fn($state) => $state ?? "There was no color change within 3 minutes after the liquid dropped on the surface that indicating materials is genuine SS304"),

                                Textarea::make('actual_result_1')
                                    ->rows(6)
                                    ->label('Actual Result')
                                    ->placeholder('Masukan Penjelasan Keadaan Sebenarnya...'),

                                Textarea::make('procedures_2')
                                    ->disabled()
                                    ->rows(1)
                                    ->formatStateUsing(fn($state) => $state ?? 'Visual check'),

                                Textarea::make('expected_result_2')
                                    ->disabled()
                                    ->rows(1)
                                    ->label('Expected Result')
                                    ->formatStateUsing(fn($state) => $state ?? 'No defect and rust found'),

                                Textarea::make('actual_result_2')
                                    ->rows(1)
                                    ->label('Actual Result')
                                    ->placeholder('Masukan Penjelasan Keadaan Sebenarnya...'),


                            ])
                            ->columns(3)
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false),

                        Repeater::make('details_tambahan')
                            ->label('Checklist Tambahan')
                            ->schema([

                                Textarea::make('procedures')
                                    // ->disabled()
                                    ->rows(3)
                                    ->label('Procedures'),

                                Textarea::make('expected_result')
                                    // ->disabled()
                                    ->rows(3)
                                    ->label('Expected Result'),

                                Textarea::make('actual_result_1')
                                    ->rows(3)
                                    ->label('Actual Result')
                                    ->placeholder('Masukan Penjelasan Keadaan Sebenarnya...'),

                            ])
                            ->default([])
                            ->addActionLabel('Tambah Checklist')
                            ->columns(3)

                    ]),

                Section::make('Summary & Quantity')
                    ->label('')
                    ->schema([
                        Grid::make(2),
                        // ->schema([
                        //     // Header kiri
                        //     Placeholder::make('summary_title')
                        //         ->content(new HtmlString('<div class="px-5 py-4 font-bold rounded-md text-lg"></div>'))
                        //         ->disableLabel(),

                        //     // Header kanan
                        //     Placeholder::make('quantity_title')
                        //         ->content(new HtmlString('<div class="px-3 py-4 font-bold rounded-md text-lg"></div>'))
                        //         ->disableLabel(),
                        // ]),

                        Grid::make(2)
                            ->schema(
                                collect(config('summarySS.fields'))->map(function ($label, $key) {
                                    return [
                                        // Kolom kiri: label
                                        Placeholder::make("summary_label_{$key}")
                                            ->content(new HtmlString('<div class="px-1 py-4 rounded-md text-md">' . e($label) . '</div>'))
                                            ->disableLabel(),

                                        // Kolom kanan: input
                                        TextInput::make("summary.{$key}")
                                            ->numeric()
                                            ->label('')
                                            ->placeholder('0')
                                            ->extraAttributes([
                                                'class' => 'px-3 py-1 border border-gray-300 text-sm w-full',
                                            ]),
                                    ];
                                })->flatten(1)->toArray()
                            ),
                    ]),

                Section::make('Catatan')
                    ->collapsible()
                    ->schema([

                        // self::textInput('remark', 'Remarks'),
                        Textarea::make('remark')
                            ->required()
                            ->label('Remarks')

                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Grid::make(1)
                                    ->schema([

                                        self::textInput('checked_name', 'Checked By'),

                                        self::signatureInput('checked_signature', ''),

                                        self::datePicker('checked_date', '')
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
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penyelesaian === 'Disetujui')
                        ->url(fn($record) => route('pdf.incomingMaterialSS', ['record' => $record->id])),
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
            'index' => Pages\ListIncommingMaterialSS::route('/'),
            'create' => Pages\CreateIncommingMaterialSS::route('/create'),
            'edit' => Pages\EditIncommingMaterialSS::route('/{record}/edit'),
            'pdfIncommingMaterialSS' => Pages\pdfIncommingMaterialSS::route('/{record}/pdfIncommingMaterialSS')
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function textArea(string $fieldName, string $label): Textarea
    {
        return Textarea::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function selectInput(string $fieldName, string $label, string $relation, string $title): Select
    {
        return
            Select::make($fieldName)
                ->relationship($relation, $title)
                ->options(function () {
                    return
                        PermintaanPembelian::with('permintaanBahanWBB')
                            ->whereDoesntHave('materialSS')
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
                    $path = SignatureUploader::handle($state, 'ttd_', 'Quality/IncommingMaterial/SS/Signatures');
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