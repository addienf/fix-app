<?php

namespace App\Filament\Resources\Production\Penyerahan;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;
use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\RelationManagers;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
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

class PenyerahanProdukJadiResource extends Resource
{
    protected static ?string $model = PenyerahanProdukJadi::class;
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'Penyerahan Produk Jadi';
    protected static ?string $pluralLabel = 'Penyerahan Produk Jadi';
    protected static ?string $modelLabel = 'Penyerahan Produk Jadi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Fieldset::make('Informasi Umum')
                    ->schema([
                        self::selectInput('spk_marketing_id', 'No SPK Marketing', 'spk', 'no_spk')
                            ->required(),
                        self::datePicker('tanggal', 'Tanggal'),
                        self::textInput('pic', 'Penanggung Jawab'),
                        self::textInput('penerima', 'Penerima')
                    ]),

                Fieldset::make('Kondisi Produk')
                    ->schema([
                        self::selectKondisi()
                            ->columnSpanFull()
                    ]),

                Fieldset::make('Catatan Tambahan')
                    ->schema([
                        self::textArea('catatan_tambahan', 'Catatan Tambahan')
                            ->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'No SPK'),
                self::textColumn('pic', 'Penanggung Jawab'),
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
