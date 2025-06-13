<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;
use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\RelationManagers;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;


class PermintaanAlatDanBahanResource extends Resource
{
    protected static ?string $model = PermintaanAlatDanBahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Permintaan Alat dan Bahan';
    protected static ?string $pluralLabel = 'Permintaan Alat dan Bahan';
    protected static ?string $modelLabel = 'Permintaan Alat dan Bahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('No SPK')
                    ->schema([
                        self::selectInput('spk_marketing_id', 'Pilih No SPK', 'spk', 'no_spk')
                            ->columnSpanFull(),
                        ToggleButtons::make('status')
                            ->label('Status Stock Barang')
                            ->boolean()
                            ->grouped(),
                    ]),
                Section::make('Informasi Umum')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('no_surat', 'No Surat'),
                                self::datePicker('tanggal', 'Tanggal')
                                    ->required(),
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
                            ])
                    ]),
                Section::make('List Detail Bahan Baku')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Repeater::make('details')
                                    ->relationship('details')
                                    ->schema([
                                        Grid::make(4)
                                            ->schema([
                                                self::textInput('bahan_baku', 'Bahan Baku')
                                                    ->extraAttributes([
                                                        'readonly' => true,
                                                        'style' => 'pointer-events: none;'
                                                    ]),
                                                self::textInput('spesifikasi', 'Spesifikasi'),
                                                self::textInput('jumlah', 'Jumlah')->numeric(),
                                                Textarea::make('keperluan_barang')
                                                    ->label('Keperluan Barang')
                                            ])
                                    ])
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->addable(false)
                                    ->columnSpanFull()
                            ])
                    ]),
                Section::make('PIC')
                    ->relationship('pic')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('create_name', 'Nama Pembuat'),
                                self::textInput('receive_name', 'Nama Penerima'),
                                self::signatureInput('create_signature', 'Dibuat Oleh'),
                                self::signatureInput('receive_signature', 'Diterima Oleh'),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'No SPK'),
                self::textColumn('no_surat', 'Nomor Surat'),
                self::textColumn('tanggal', 'Tanggal Dibuat')->date('d M Y'),
                self::textColumn('status', 'Status Stock Barang')
                    ->badge()
                    ->default(null)
                    ->placeholder('Belum Diproses')
                    ->formatStateUsing(function ($state) {
                        if (is_null($state)) {
                            return 'Belum Diproses';
                        }
                        return $state ? 'Ready' : 'Not Ready';
                    })
                    ->color(function ($state) {
                        if (is_null($state)) {
                            return 'warning';
                        }
                        return $state ? 'success' : 'danger';
                    }),
                // self::textColumn('dari', 'Dari'),
                // self::textColumn('kepada', 'Kepada'),
                // ImageColumn::make('pic.create_signature')
                //     ->label('Pembuat')
                //     ->width(150)
                //     ->height(75),
                // ImageColumn::make('pic.receive_signature')
                //     ->label('Penyetuju')
                //     ->width(150)
                //     ->height(75),
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
                        ->url(fn($record) => self::getUrl('pdfPermintaanAlatdanBahan', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanAlatDanBahans::route('/'),
            'create' => Pages\CreatePermintaanAlatDanBahan::route('/create'),
            'edit' => Pages\EditPermintaanAlatDanBahan::route('/{record}/edit'),
            'pdfPermintaanAlatdanBahan' => Pages\pdfPermintaanAlatdanBahan::route('/{record}/pdfPermintaanAlatdanBahan')
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
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state)
                        return;

                    // Ambil data SPK dan relasi yang dibutuhkan
                    $spk = SPKMarketing::with(['jadwalProduksi.sumber'])->find($state);
                    if (!$spk || !$spk->jadwalProduksi?->sumber)
                        return;

                    $bahanBaku = $spk->jadwalProduksi->sumber->bahan_baku ?? [];

                    // Ubah bahan baku jadi array yang bisa ditangkap Repeater
                    $details = collect($bahanBaku)->map(function ($item) {
                        return [
                            'bahan_baku' => is_array($item) ? ($item['bahan_baku'] ?? '') : $item,
                        ];
                    })->toArray();

                    // Set ke form
                    $set('dari', $spk->dari);
                    $set('kepada', $spk->kepada);
                    $set('details', $details);
                });
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
                    $path = SignatureUploader::handle($state, 'ttd_', 'Production/PermintaanBahan/Signatures');
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