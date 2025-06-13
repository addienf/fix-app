<?php

namespace App\Filament\Resources\Warehouse\Incomming;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;
use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\RelationManagers;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
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

class IncommingMaterialResource extends Resource
{
    protected static ?string $model = IncommingMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?string $navigationLabel = 'Incoming Material';
    protected static ?string $pluralLabel = 'Incoming Material';
    protected static ?string $modelLabel = 'Incoming Material';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Fieldset::make('')
                    ->schema([
                        self::selectInput('permintaan_pembelian_id', 'No', 'permintaanPembelian', 'id')
                            ->required(),
                        self::datePicker('tanggal', 'Tanggal Penerimaan'),
                    ]),

                Fieldset::make('')
                    ->schema([
                        self::textInput('kondisi_material', 'Pemeriksaan Material'),
                        self::textInput('status_penerimaan', 'Status Penerimaan'),
                        self::textInput('dokumen_pendukung', 'Dokumen Pendukung'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('permintaan_pembelian_id', 'No'),
                self::textColumn('tanggal', 'Tanggal Penerimaan'),
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
                        ->url(fn($record) => self::getUrl('pdfIncommingMaterial', ['record' => $record->id])),
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
            'index' => Pages\ListIncommingMaterials::route('/'),
            'create' => Pages\CreateIncommingMaterial::route('/create'),
            'edit' => Pages\EditIncommingMaterial::route('/{record}/edit'),
            'pdfIncommingMaterial' => Pages\pdfIncommingMaterial::route('/{record}/pdfIncommingMaterial')
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
                    $path = SignatureUploader::handle($state, 'ttd_', 'Warehpuse/IncommingMaterial/Signatures');
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