<?php

namespace App\Filament\Resources\Warehouse\Pelabelan;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;
use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\RelationManagers;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
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

class QCPassedResource extends Resource
{
    protected static ?string $model = QCPassed::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Pelabelan QC Passed';
    protected static ?string $pluralLabel = 'Pelabelan QC Passed';
    protected static ?string $modelLabel = 'Pelabelan QC Passed';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Fieldset::make('Informasi Umum')
                    ->schema([
                        self::selectInput('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                            ->required(),
                        self::datePicker('tanggal', 'Tanggal'),
                        self::textInput('penanggung_jawab', 'Penanggung Jawab')
                    ])->columns(3),

                Fieldset::make('Detail Laporan Produk')
                    ->relationship('detail')
                    ->schema([
                        self::textInput('nama_produk', 'Nama Produk'),
                        self::textInput('tipe', 'Tipe/Model'),
                        self::textInput('serial_number', 'S/N'),
                        // self::textInput('jenis_transaksi', 'Jenis Transaksi (Masuk/Keluar)'),
                        self::selectJenis(),
                        self::textInput('jumlah', 'Jumlah'),
                        self::textInput('keterangan', 'Keterangan')
                    ])->columns(3),

                Fieldset::make('Syarat dan Ketentuan')
                    ->schema([
                        self::textInput('total_masuk', 'Total Masuk'),
                        self::textInput('total_keluar', 'Total Keluar'),
                        self::textInput('sisa_stock', 'Sisa Stock')
                    ])->columns(3),

                Fieldset::make('PIC')
                    ->relationship('pic')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('created_name', 'Dibuat Oleh'),
                                self::textInput('approved_name', 'Disetujui Oleh'),
                                self::signatureInput('created_signature', ''),
                                self::signatureInput('approved_signature', ''),
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
                self::textColumn('penanggung_jawab', 'Penanggung Jawab'),
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
                        ->url(fn($record) => self::getUrl('pdfPelabelanQCPassed', ['record' => $record->id])),
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
