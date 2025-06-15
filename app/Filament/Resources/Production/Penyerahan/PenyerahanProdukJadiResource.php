<?php

namespace App\Filament\Resources\Production\Penyerahan;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;
use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\RelationManagers;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PenyerahanProdukJadiResource extends Resource
{
    protected static ?string $model = PenyerahanProdukJadi::class;
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Penyerahan Produk Jadi';
    protected static ?string $pluralLabel = 'Penyerahan Produk Jadi';
    protected static ?string $modelLabel = 'Penyerahan Produk Jadi';
    protected static ?string $slug = 'produksi/penyerahan-produk-jadi';

    public static function getNavigationBadge(): ?string
    {
        $count = PenyerahanProdukJadi::where('status_penerimaan', '!=', 'Diterima')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
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

                                self::selectInputSPK()
                                    ->placeholder('Pilih No SPK'),

                                self::datePicker('tanggal', 'Tanggal'),

                                self::textInput('penanggug_jawab', 'Penanggung Jawab'),

                                self::textInput('penerima', 'Penerima'),

                            ]),

                    ]),

                Section::make('Detail Jadwal Produksi')
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

                                self::textInput('tipe', 'Tipe/Model')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('volume', 'Volume')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('no_seri', 'No Seri')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('jumlah', 'Jumlah')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('no_spk', 'No SPK')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                            ])
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false),

                    ]),

                Section::make('Kondisi Produk')
                    ->collapsible()
                    ->schema([

                        self::selectKondisi()
                            ->placeholder('Pilih Kondisi')
                            ->columnSpanFull()

                    ]),

                Section::make('Catatan Tambahan')
                    ->collapsible()
                    ->schema([

                        self::textArea('catatan_tambahan', 'Catatan Tambahan')
                            ->columnSpanFull()

                    ]),

                Section::make('Detail PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('submit_name', 'Diserahkan Oleh'),

                                        self::signatureInput('submit_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('receive_name', 'Diterima Oleh'),

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
                self::textColumn('spk.no_spk', 'No SPK'),
                self::textColumn('penanggug_jawab', 'Penanggung Jawab'),
                TextColumn::make('status_penerimaan')
                    ->label('Status Penerimaan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Diterima' ? 'success' : 'danger'
                    )
                    ->alignCenter(),
                ImageColumn::make('pic.submit_signature')
                    ->width(150)
                    ->label('Diserahkan Oleh')
                    ->height(75),
                ImageColumn::make('pic.receive_signature')
                    ->width(150)
                    ->label('Diterima Oleh')
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
                        ->visible(fn($record) => $record->status_penerimaan === 'Diterima')
                        ->url(fn($record) => self::getUrl('pdfPenyerahanProdukJadi', ['record' => $record->id])),
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
            'index' => Pages\ListPenyerahanProdukJadis::route('/'),
            'create' => Pages\CreatePenyerahanProdukJadi::route('/create'),
            'edit' => Pages\EditPenyerahanProdukJadi::route('/{record}/edit'),
            'pdfPenyerahanProdukJadi' => Pages\pdfPenyerahanProdukJadi::route('/{record}/pdfPenyerahanProdukJadi')
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
                    ->whereHas('spkQC', function ($query) {
                        $query->where('status_penerimaan', 'Diterima');
                    })->whereDoesntHave('produkJadi')
            )
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spk = SPKMarketing::with('spesifikasiProduct.urs', 'spesifikasiProduct.details.product', 'jadwalProduksi', 'pengecekanSS.penyerahan')->find($state);
                if (!$spk) return;

                $spesifikasi = $spk->spesifikasiProduct;
                $no_spk = $spk->no_spk;
                $tipe = $spk?->jadwalProduksi?->details->first()->tipe;
                $volume = $spk?->jadwalProduksi?->details->first()->volume;
                $no_seri = $spk?->pengecekanSS?->penyerahan->no_seri;

                $details = $spesifikasi->details->map(function ($detail) use ($no_spk, $tipe, $volume, $no_seri) {
                    return [
                        'nama_produk' => $detail->product?->name ?? '-',
                        'jumlah' => $detail?->quantity ?? '-',
                        'no_seri' => $no_seri ?? '-',
                        'no_spk' => $no_spk ?? '-',
                        'tipe' => $tipe  ?? '-',
                        'volume' => $volume  ?? '-'
                    ];
                })->toArray();

                // dd($details);
                $set('details', $details);
            })
        ;
    }

    protected static function selectKondisi(): Select
    {
        return
            Select::make('kondisi_produk')
            ->label('Kondisi Produk')
            ->required()
            ->placeholder('Pilih Kondisi Produk')
            ->options([
                'baik' => 'Baik',
                'rusak' => 'Rusak',
                'perlu_perbaikan' => 'Perlu Perbaikan'
            ]);
    }

    protected static function datePicker(string $fieldName, string $label): DatePicker
    {
        return
            DatePicker::make($fieldName)
            ->label($label)
            ->required()
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
            ->required();
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
