<?php

namespace App\Filament\Resources\Engineering\SPK;

use App\Filament\Resources\Engineering\SPK\SPKServiceResource\Pages;
use App\Filament\Resources\Engineering\SPK\SPKServiceResource\RelationManagers;
use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\SPK\SPKService;
use App\Services\SignatureUploader;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class SPKServiceResource extends Resource
{
    protected static ?string $model = SPKService::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'SPK Service';
    protected static ?string $pluralLabel = 'SPK Service';
    protected static ?string $modelLabel = 'SPK Service';
    protected static ?string $slug = 'engineering/spk-service';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationBadge(): ?string
    {
        $count = SPKService::where('status_penyelesaian', '!=', 'Selesai')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        $lastValue = SPKService::latest('no_spk_service')->value('no_spk_service');
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diselesaikan'),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        self::selectSpecInput()
                            ->columnSpanFull()
                            ->hiddenOn('edit'),

                        TextInput::make('no_spk_service')
                            ->label('Nomor SPK Service')
                            ->hint('Format: XXX/QKS/ENG/SPK/MM/YY')
                            ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                            ->hiddenOn('edit')
                            ->unique(ignoreRecord: true)
                            ->required(),

                        DatePicker::make('tanggal')
                            ->required(),

                        TextInput::make('alamat')
                            ->required(),

                        TextInput::make('perusahaan')
                            ->label('Nama Perusahaan')
                            ->required()
                    ])->columns($isEdit ? 3 : 2),

                Section::make('Deskripsi Pekerjaan')
                    ->collapsible()
                    ->schema([
                        Select::make('deskripsi_pekerjaan')
                            ->multiple()
                            ->options([
                                'service' => 'Service',
                                'maintenance' => 'Maintenance',
                                'lainya' => 'Lainnya'
                            ])
                            ->columnSpanFull(),

                        DatePicker::make('jadwal_pelaksana')
                            ->required(),

                        DatePicker::make('waktu_selesai')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Petugas')
                    ->collapsible()
                    ->schema([
                        Repeater::make('petugas')
                            ->label('')
                            ->relationship('petugas')
                            ->schema([
                                TextInput::make('nama_teknisi')
                                    ->label('Nama Teknisi')
                                    ->required(),

                                TextInput::make('jabatan')
                                    ->required(),

                                self::signatureInput('ttd', 'Tanda Tangan')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible()
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Data Petugas'),
                    ]),

                Section::make('Pemeriksaan dan Persetujuan')
                    ->collapsible()
                    ->hiddenOn('create')
                    ->relationship('pemeriksaanPersetujuan')
                    ->schema([
                        ButtonGroup::make('status_pekerjaan')
                            ->label('Pekerjaan Telah Selesai ? (Ya/Tidak)')
                            ->required()
                            ->gridDirection('row')
                            ->options([
                                'ya' => 'Ya',
                                'tidak' => 'Tidak'
                            ]),

                        Textarea::make('catatan_tambahan')
                            ->required()
                            ->label('Catatan Tambahan')
                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([
                                        Hidden::make('dikonfirmasi_nama')
                                            ->default(fn() => auth()->id()),

                                        self::textInput('dikonfirmasi_nama_placeholder', 'Dikonfirmasi Oleh,')
                                            ->default(fn() => auth()->user()?->name)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('dikonfirmasi_nama', 'Dikonfirmasi Oleh,'),

                                        self::signatureInput('dikonfirmasi_ttd', ''),

                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([

                                        Hidden::make('diketahui_nama')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        self::textInput('diketahui_nama_placeholder', 'Diketahui Oleh,')
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->required(false)
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),

                                        // self::textInput('diketahui_nama', 'Diketahui Oleh,'),

                                        self::signatureInput('diketahui_ttd', ''),

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
                TextColumn::make('complain.form_no')
                    ->label('No Compaint Form'),

                TextColumn::make('no_spk_service')
                    ->label('No SPK Service'),

                TextColumn::make('tanggal')
                    ->date('d M Y'),

                TextColumn::make('perusahaan')
                    ->label('Nama Perusahaan'),

                TextColumn::make('pemeriksaanPersetujuan.status_pekerjaan')
                    ->label('Status Pengerjaan')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if (strtolower($state) === 'ya') {
                            return 'Selesai';
                        }

                        return 'Belum Selesai';
                    })
                    ->color(function ($state) {
                        return strtolower($state) === 'ya' ? 'success' : 'danger';
                    })
                    ->alignCenter(),

                TextColumn::make('status_penyelesaian')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Selesai' ? 'success' : 'danger'
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
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn($record) => $record->status_penyelesaian === 'Selesai')
                        ->url(fn($record) => route('pdf.spkService', ['record' => $record->id])),
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
            'index' => Pages\ListSPKServices::route('/'),
            'create' => Pages\CreateSPKService::route('/create'),
            'edit' => Pages\EditSPKService::route('/{record}/edit'),
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Engineering/SPK/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
