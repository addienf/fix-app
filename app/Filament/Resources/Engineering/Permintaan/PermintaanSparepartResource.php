<?php

namespace App\Filament\Resources\Engineering\Permintaan;

use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\Pages;
use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\RelationManagers;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\SPK\SPKService;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class PermintaanSparepartResource extends Resource
{
    protected static ?string $model = PermintaanSparepart::class;
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Permintaan Spareparts';
    protected static ?string $pluralLabel = 'Permintaan Spareparts';
    protected static ?string $modelLabel = 'Permintaan Spareparts';
    protected static ?string $slug = 'engineering/permintaan-spareparts';

    public static function getNavigationBadge(): ?string
    {
        $count = PermintaanSparepart::where('status_penyerahan', '!=', 'Diserahkan')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $lastValue = PermintaanSparepart::latest('no_surat')->value('no_surat');
        $isEdit = $form->getOperation() === 'edit';
        return $form
            ->schema([
                //
                Hidden::make('status_penyerahan')
                    ->default('Belum Diketahui'),

                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    ->options(function () {
                        return SPKService::where('status_penyelesaian', 'Selesai')
                            ->whereDoesntHave('permintaanSparepart')
                            ->pluck('no_spk_service', 'id');
                    })
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull()
                    ->hiddenOn(operations: 'edit'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([

                        TextInput::make('no_surat')
                            ->label('Nomor Surat')
                            ->hint('Format: No Surat')
                            ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                            ->hiddenOn('edit')
                            ->unique(ignoreRecord: true)
                            ->required(),

                        DatePicker::make('tanggal')
                            ->required(),

                        TextInput::make('dari')
                            ->required()
                            ->placeholder('Engineer'),

                        TextInput::make('kepada')
                            ->required()
                            ->placeholder('Warehouse')
                    ])
                    ->columns($isEdit ? 3 : 2),

                Section::make('List Spareparts')
                    ->collapsible()
                    ->schema([
                        TableRepeater::make('details')
                            ->relationship('details')
                            ->label('')
                            ->schema([
                                TextInput::make('bahan_baku')
                                    ->required()
                                    ->label('Bahan baku'),

                                TextInput::make('spesifikasi')
                                    ->required(),

                                TextInput::make('jumlah')
                                    ->required()
                                    ->numeric(),

                                TextInput::make('keperluan_barang')
                                    ->required()
                                    ->label('Keperluan Barang'),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->collapsible()
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Data Petugas'),
                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Grid::make(1)
                                    ->schema([

                                        self::textInput('dibuat_name', 'Dibuat Oleh'),

                                        self::signatureInput('dibuat_ttd', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('diketahui_name', 'Diketahui Oleh'),

                                        self::signatureInput('diketahui_ttd', ''),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || filled($record?->diketahui_ttd)
                                    ),

                                Grid::make(1)
                                    ->schema([

                                        self::textInput('diserahkan_name', 'Diserahkan Kepada'),

                                        self::signatureInput('diserahkan_ttd', ''),

                                    ])->hidden(
                                        fn($operation, $record) =>
                                        $operation === 'create' || blank($record?->diketahui_ttd) || filled($record?->diserahkan_ttd)
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
                TextColumn::make('spkService.no_spk_service')
                    ->label('No SPK Service'),

                TextColumn::make('tanggal')
                    ->date('d M Y'),

                TextColumn::make('status_penyerahan')
                    ->label('Status')
                    ->badge()
                    ->color(function ($record) {
                        $penyerahan = $record->status_penyerahan;
                        $persetujuan = $record->status_persetujuan;

                        if ($penyerahan === 'Diserahkan') {
                            return 'success';
                        }

                        if ($penyerahan !== 'Diketahui' && $persetujuan !== 'Diserahkan') {
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
                        ->visible(fn($record) => $record->status_penyerahan === 'Diserahkan')
                        ->url(fn($record) => route('pdf.sparepartAlatKerja', ['record' => $record->id])),
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
            'index' => Pages\ListPermintaanSpareparts::route('/'),
            'create' => Pages\CreatePermintaanSparepart::route('/create'),
            'edit' => Pages\EditPermintaanSparepart::route('/{record}/edit'),
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Engineering/PermintaanSparepart/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
