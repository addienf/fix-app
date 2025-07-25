<?php

namespace App\Filament\Resources\Production\SPK;

use App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;
use App\Filament\Resources\Production\SPK\SPKQualityResource\RelationManagers;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
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
use Illuminate\Support\Carbon;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class SPKQualityResource extends Resource
{
    protected static ?string $model = SPKQuality::class;
    protected static ?int $navigationSort = 13;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'SPK Quality';
    protected static ?string $pluralLabel = 'SPK Quality';
    protected static ?string $modelLabel = 'SPK Quality';
    protected static ?string $slug = 'produksi/spk-quality';


    public static function getNavigationBadge(): ?string
    {
        $count = SPKQuality::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }
    public static function form(Form $form): Form
    {
        $lastValue = SPKQuality::latest('no_spk')->value('no_spk');
        $isEdit = $form->getOperation() === 'edit';
        return $form
            ->schema([
                //
                Hidden::make('status_penerimaan')
                    ->default('Belum Diterima'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                self::textInput('no_spk', 'Nomor SPK Quality')
                                    ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan($isEdit ? 'full' : 1)
                                    ->hint('Format: XXX/QKS/PRO/SPK/MM/YY'),

                                self::selectInputSPK()
                                    ->placeholder('Pilih Nomor SPK')
                                    ->hiddenOn('edit'),

                                self::textInput('dari', 'Dari')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('kepada', 'Kepada')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                            ]),
                    ]),

                Section::make('Detail Produk Yang Dipesan')
                    // ->hiddenOn(operations: 'edit')
                    ->collapsible()
                    ->schema([
                        TableRepeater::make('details')
                            ->relationship('details')
                            ->label('')
                            ->schema([
                                self::textInput('nama_produk', 'Nama Produk')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                self::textInput('jumlah', 'Jumlah Pesanan')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                self::textInput('no_urs', 'No URS')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('rencana_pengiriman', 'Rencana Pengiriman')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                            ])
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false),
                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('create_name')
                                            ->default(fn() => auth()->id()),

                                        self::textInput('create_name_placeholder', 'Yang Membuat')
                                            ->default(fn() => auth()->user()?->name)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('create_name', 'Yang Membuat')
                                        //     ->placeholder('Produksi'),

                                        self::signatureInput('create_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('receive_name')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        self::textInput('receive_name_placeholder', 'Yang Menerima')
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->required(false)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('receive_name', 'Yang Menerima')
                                        //     ->placeholder('Quality'),

                                        self::signatureInput('receive_signature', ''),

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
                self::textColumn('spk.no_spk', 'No SPK Marketing'),

                self::textColumn('no_spk', 'No SPK QUality'),

                self::textColumn('dari', 'Dari'),

                self::textColumn('kepada', 'Kepada'),

                TextColumn::make('status_penerimaan')
                    ->label('Status Penerimaan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Diterima' ? 'success' : 'danger'
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
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima')
                        ->url(fn($record) => route('pdf.spkQuality', ['record' => $record->id])),
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
            'index' => Pages\ListSPKQualities::route('/'),
            'create' => Pages\CreateSPKQuality::route('/create'),
            'edit' => Pages\EditSPKQuality::route('/{record}/edit'),
            'pdfSPKQuality' => Pages\pdfSPKQuality::route('/{record}/pdfSPKQuality')
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
                    ->whereHas('pengecekanSS.penyerahan', function ($query) {
                        $query->where('status_penyelesaian', 'Disetujui');
                    })->whereDoesntHave('spkQC')
            )
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spk = SPKMarketing::with('spesifikasiProduct.urs', 'spesifikasiProduct.details.product')->find($state);
                if (!$spk) return;

                $spesifikasi = $spk->spesifikasiProduct;
                $noUrs = $spesifikasi?->urs?->no_urs ?? '-';
                $rencanaPengiriman = $spk->tanggal ? Carbon::parse($spk->tanggal)->format('d/m/Y') : '-';
                $dari = $spk->dari ?? '-';
                $kepada = $spk->kepada ?? '-';

                $details = $spesifikasi->details->map(function ($detail) use ($noUrs, $rencanaPengiriman, $dari, $kepada) {
                    return [
                        'nama_produk' => $detail->product?->name ?? '-',
                        'jumlah' => $detail?->quantity ?? '-',
                        'no_urs' => $noUrs ?? '-',
                        'rencana_pengiriman' => $rencanaPengiriman ?? '-',
                    ];
                })->toArray();

                // dd($details);
                $set('details', $details);
                $set('dari', $dari ?? '-');
                $set('kepada', $kepada ?? '-');
            });
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Production/PenyerahanElectrical/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }

    protected static function textArea(string $fieldName, string $label): Textarea
    {
        return
            Textarea::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
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
