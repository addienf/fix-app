<?php

namespace App\Filament\Resources\Engineering\Berita;

use App\Filament\Resources\Engineering\Berita\BeritaAcaraResource\Pages;
use App\Filament\Resources\Engineering\Berita\BeritaAcaraResource\RelationManagers;
use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\SPK\SPKService;
use App\Services\SignatureUploader;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class BeritaAcaraResource extends Resource
{
    protected static ?string $model = BeritaAcara::class;
    protected static ?int $navigationSort = 22;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Berita Acara';
    protected static ?string $pluralLabel = 'Berita Acara';
    protected static ?string $modelLabel = 'Berita Acara';
    protected static ?string $slug = 'engineering/berita-acara';

    public static function form(Form $form): Form
    {
        $lastValue = BeritaAcara::latest('no_surat')->value('no_surat');
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Fieldset::make('No SPK Service')
                    ->label('')
                    ->schema([
                        Select::make('spk_service_id')
                            ->label('Nomor SPK Service')
                            ->options(function () {
                                return
                                    SPKService::whereHas('permintaanSparepart', function ($query) {
                                        $query->where('status_penyerahan', 'Diserahkan');
                                    })
                                    ->whereDoesntHave('beritaAcara')
                                    ->pluck('no_spk_service', 'id');
                            })
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->columnSpanFull()
                            ->hiddenOn(operations: 'edit')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state) return;

                                $service = SPKService::with('petugas')->find($state);
                                if (!$service) return;

                                $namaPetugas = $service->petugas->pluck('nama_teknisi')->toArray();
                                $nama_teknisi = implode(', ', $namaPetugas);

                                // dd($nama_teknisi);
                                $set('detail.nama_teknisi', $nama_teknisi);
                            }),
                    ]),

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
                    ])
                    ->columns(2),

                Split::make([
                    Section::make('Data Penyedia Jasa')
                        ->collapsible()
                        ->relationship('penyediaJasa')
                        ->schema([
                            TextInput::make('nama')
                                ->required(),

                            TextInput::make('perusahaan')
                                ->required(),

                            TextInput::make('alamat')
                                ->required(),

                            TextInput::make('jabatan')
                                ->required(),
                        ])
                        ->columns(2),

                    Section::make('Data Pelanggan')
                        ->collapsible()
                        ->relationship('pelanggan')
                        ->schema([
                            TextInput::make('nama')
                                ->required(),

                            TextInput::make('perusahaan')
                                ->required(),

                            TextInput::make('alamat')
                                ->required(),

                            TextInput::make('jabatan')
                                ->required(),
                        ])
                        ->columns(2),
                ])
                    ->columnSpanFull(),

                Section::make('Detail Pekerjaan')
                    ->relationship('detail')
                    ->collapsible()
                    ->schema([
                        Select::make('jenis_pekerjaan')
                            ->required()
                            ->label('Jenis Pekerjaan')
                            ->placeholder('Pilih Jenis Pekerjaan')
                            ->options([
                                'service' => 'Service',
                                'maintenance' => 'Maintenance'
                            ]),

                        Textarea::make('detail_pekerjaan')
                            ->required()
                            ->rows(1)
                            ->label('Deskripsi Pekerjaan'),

                        TextInput::make('lokasi_pengerjaan')
                            ->required()
                            ->label('Lokasi Pengerjaan'),

                        TextInput::make('nama_teknisi')
                            ->required()
                            ->label('Nama Teknisi')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ])
                    ])
                    ->columns(2),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        TextInput::make('jasa_name')
                            ->required()
                            ->label('Nama Penyedia Jasa'),

                        self::signatureInput('jasa_ttd', ''),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('spkService.no_spk_service')
                    ->label('No SPK Service'),

                TextColumn::make('no_surat')
                    ->label('No Surat'),

                TextColumn::make('tanggal')
                    ->date('d M Y'),
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
                        ->url(fn($record) => route('pdf.beritaAcara', ['record' => $record->id])),
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
            'index' => Pages\ListBeritaAcaras::route('/'),
            'create' => Pages\CreateBeritaAcara::route('/create'),
            'edit' => Pages\EditBeritaAcara::route('/{record}/edit'),
        ];
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Engineering/BeritaAcara/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
