<?php

namespace App\Filament\Resources\Sales\SPK;

use App\Filament\Resources\Sales\SPK\SPKResource\Pages;
use App\Filament\Resources\Sales\SPK\SPKResource\RelationManagers;
use App\Filament\Resources\Sales\SPK\Traits\DetailProduk;
use App\Filament\Resources\Sales\SPK\Traits\InformasiUmum;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use App\Traits\HasAutoNumber;
use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use GuzzleHttp\Promise\Create;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class SPKResource extends Resource
{
    use SimpleFormResource, HasSignature, HasAutoNumber, InformasiUmum, DetailProduk;
    protected static ?string $model = SPKMarketing::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'sales/spk-marketing';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'SPK Marketing';
    protected static ?string $pluralLabel = 'SPK Marketing';
    protected static ?string $modelLabel = 'SPK Marketing';

    public static function getSlug(): string
    {
        return 'spk-marketing';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = SPKMarketing::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        // $lastValue = SPKMarketing::latest('no_spk')->value('no_spk');

        return $form
            ->schema([
                Hidden::make('status_penerimaan')
                    ->default('Belum Diterima'),

                self::informasiUmumSection(),

                self::detailProdukSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'create',
                            'role' => 'Yang Membuat',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'receive',
                            'role' => 'Yang Menerima',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->receive_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Sales/SPK/Signatures'
                )

                //
                // Section::make('Informasi Umum')
                //     ->collapsible()
                //     ->schema([

                //         Grid::make(2)
                //             ->schema([

                //                 DatePicker::make('tanggal')
                //                     ->label('Rencana Pengiriman')
                //                     ->required()
                //                     ->displayFormat('M d Y'),

                //                 self::textInput('no_spk', 'Nomor SPK')
                //                     ->hint('Format: XXX/QKS/MKT/SPK/MM/YY')
                //                     ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                //                     ->hiddenOn('edit')
                //                     ->unique(ignoreRecord: true),

                //                 self::selectSpecInput()
                //                     ->hiddenOn('edit'),

                //                 self::textInput('dari', 'Dari'),

                //                 self::textInput('no_order', 'Nomor Order')
                //                     ->unique(ignoreRecord: true),

                //                 self::textInput('kepada', 'Kepada'),

                //                 Hidden::make('status_penerimaan')
                //                     ->default('Belum Diterima'),

                //             ]),

                //     ]),

                // Section::make('Detail Produk Yang Dipesan')
                //     ->hiddenOn(operations: 'edit')
                //     ->collapsible()
                //     ->schema([

                //         TableRepeater::make('details')
                //             ->label('')
                //             ->schema([

                //                 self::textInput('name', 'Nama Produk')
                //                     ->extraAttributes([
                //                         'readonly' => true,
                //                         'style' => 'pointer-events: none;'
                //                     ]),

                //                 self::textInput('quantity', 'Jumlah Pesanan')
                //                     ->extraAttributes([
                //                         'readonly' => true,
                //                         'style' => 'pointer-events: none;'
                //                     ]),

                //                 self::textInput('no_urs', 'No URS')
                //                     ->extraAttributes([
                //                         'readonly' => true,
                //                         'style' => 'pointer-events: none;'
                //                     ]),

                //             ])
                //             ->deletable(false)
                //             ->reorderable(false)
                //             ->addable(false)
                //             ->helperText('*Salinan URS Wajib diberikan kepada Departemen Produksi'),

                //     ]),

                // Section::make('PIC')
                //     ->collapsible()
                //     ->relationship('pic')
                //     ->schema([

                //         Grid::make(2)
                //             ->schema([

                //                 Grid::make(1)
                //                     ->schema([
                //                         Hidden::make('create_name')
                //                             ->default(fn() => auth()->id()),

                //                         self::textInput('create_name_placeholder', 'Yang Membuat')
                //                             ->default(fn() => auth()->user()?->name)
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),

                //                         self::signatureInput('create_signature', ''),

                //                     ])->hiddenOn(operations: 'edit'),

                //                 Grid::make(1)
                //                     ->schema([
                //                         Hidden::make('receive_name')
                //                             ->default(fn() => auth()->id())
                //                             ->dehydrated(true)
                //                             ->afterStateHydrated(function ($component) {
                //                                 $component->state(auth()->id());
                //                             }),

                //                         self::textInput('receive_name_placeholder', 'Yang Menerima')
                //                             ->placeholder(fn() => auth()->user()?->name)
                //                             ->required(false)
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),

                //                         self::signatureInput('receive_signature', ''),

                //                     ])->hiddenOn(operations: 'create'),

                //             ]),

                //     ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spesifikasiProduct.urs.no_urs', 'No URS'),

                self::textColumn('no_order', 'No Order'),

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
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data SPK Marketing')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->tooltip('Lihat Dokumen PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->url(fn($record) => route('pdf.SPKMarketing', ['record' => $record->id]))
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima'),
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
            'index' => Pages\ListSPKS::route('/'),
            'create' => Pages\CreateSPK::route('/create'),
            'edit' => Pages\EditSPK::route('/{record}/edit'),
            'pdfSPK' => Pages\pdfView::route('/{record}/pdfSPK')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                // 'spesifikasiProduct.urs.no_urs',
                'spesifikasiProduct.urs',
                'pic',
            ]);
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
    //         ->required();
    // }

    // protected static function selectSpecInput(): Select
    // {
    //     return
    //         Select::make('spesifikasi_product_id')
    //         ->label('Nama Customer')
    //         ->placeholder('Pilih Nama Customer')
    //         ->reactive()
    //         ->required()
    //         ->options(function () {
    //             return
    //                 SpesifikasiProduct::with('urs.customer')
    //                 ->whereDoesntHave('spk')
    //                 ->get()
    //                 ->mapWithKeys(function ($item) {
    //                     $noUrs = $item->urs->no_urs ?? '-';
    //                     $customerName = $item->urs->customer->name ?? '-';
    //                     return [$item->id => "{$noUrs} - {$customerName}"];
    //                 });
    //         })
    //         ->afterStateUpdated(function ($state, callable $set) {
    //             if (!$state) return;

    //             $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'details.product'])->find($state);
    //             if (!$spesifikasi) return;

    //             $noUrs = $spesifikasi->urs?->no_urs ?? '-';

    //             // Ubah details ke bentuk array yang bisa ditangkap Repeater
    //             $details = $spesifikasi->details->map(function ($detail) use ($noUrs) {
    //                 return [
    //                     'name' => $detail->product?->name ?? '-',
    //                     'quantity' => $detail?->quantity ?? '-',
    //                     'no_urs' => $noUrs,
    //                 ];
    //             })->toArray();
    //             $set('details', $details);
    //         });
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
    //             $path = SignatureUploader::handle($state, 'ttd_', 'Sales/SPK/Signatures');
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
