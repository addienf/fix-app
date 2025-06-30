<?php

namespace App\Filament\Resources\Warehouse\Pelabelan;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;
use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\RelationManagers;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
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

class QCPassedResource extends Resource
{
    protected static ?string $model = QCPassed::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Pelabelan QC Passed';
    protected static ?string $pluralLabel = 'Pelabelan QC Passed';
    protected static ?string $modelLabel = 'Pelabelan QC Passed';
    protected static ?string $slug = 'warehouse/pelabelan-qc-passed';

    public static function getNavigationBadge(): ?string
    {
        $count = QCPassed::where('status_persetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status_persetujuan')
                    ->default('Belum Disetujui'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([

                        self::selectInputSPK()
                            ->placeholder('Pilih No SPK'),

                        self::datePicker('tanggal', 'Tanggal'),

                        self::textInput('penanggung_jawab', 'Penanggung Jawab')

                    ])->columns(3),

                Section::make('Detail Laporan Produk')
                    ->collapsible()
                    ->schema([
                        TableRepeater::make('details')
                            ->relationship('details')
                            ->schema([
                                self::textInput('nama_produk', 'Nama Produk'),

                                self::textInput('tipe', 'Tipe/Model'),

                                self::textInput('serial_number', 'S/N'),

                                self::selectJenis(),

                                self::textInput('jumlah', 'Jumlah'),

                                self::textInput('keterangan', 'Keterangan')

                            ])
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false)
                    ]),

                Section::make('Syarat dan Ketentuan')
                    ->schema([

                        self::textInput('total_masuk', 'Total Masuk'),

                        self::textInput('total_keluar', 'Total Keluar'),

                        self::textInput('sisa_stock', 'Sisa Stock')

                    ])->columns(3),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('created_name', 'Dibuat Oleh'),

                                        self::signatureInput('created_signature', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('approved_name', 'Disetujui Oleh'),

                                        self::signatureInput('approved_signature', ''),

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

                self::textColumn('penanggung_jawab', 'Penanggung Jawab'),

                TextColumn::make('status_persetujuan')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Disetujui' ? 'success' : 'danger'
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
                        ->visible(fn($record) => $record->status_persetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.PelabelanQCPassed', ['record' => $record->id])),
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
            'index' => Pages\ListQCPasseds::route('/'),
            'create' => Pages\CreateQCPassed::route('/create'),
            'edit' => Pages\EditQCPassed::route('/{record}/edit'),
            'pdfPelabelanQCPassed' => Pages\pdfPelabelanQCPassed::route('/{record}/pdfPelabelanQCPassed')
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
                    ->whereHas('pengecekanPerforma', function ($query) {
                        $query->where('status_penyelesaian', 'Disetujui');
                    })->whereDoesntHave('qc')
            )
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spk = SPKMarketing::with('spesifikasiProduct.details.product', 'pengecekanElectrical', 'pengecekanPerforma')->find($state);
                if (!$spk) return;

                $spesifikasi = $spk->spesifikasiProduct;
                $serial = $spk->pengecekanPerforma?->serial_number;
                $tipe = $spk?->pengecekanElectrical?->tipe;

                $details = $spesifikasi->details->map(function ($detail) use ($serial, $tipe) {
                    return [
                        'nama_produk' => $detail->product?->name ?? '-',
                        'jumlah' => $detail?->quantity ?? '-',
                        'serial_number' => $serial ?? '-',
                        'tipe' => $tipe  ?? '-',
                    ];
                })->toArray();

                // dd($details);
                $set('details', $details);
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

    protected static function selectJenis(): Select
    {
        return
            Select::make('jenis_transaksi')
            ->label('Jenis Transaksi')
            ->required()
            ->placeholder('Pilih Jenis Transaksi')
            ->options([
                'masuk' => 'Masuk',
                'keluar' => 'Keluar',
            ]);
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
