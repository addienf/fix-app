<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;
use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\RelationManagers;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
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
use Wallo\FilamentSelectify\Components\ButtonGroup;

class IncommingMaterialNonSSResource extends Resource
{
    protected static ?string $model = IncommingMaterialNonSS::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 18;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Incoming Material Non SS';
    protected static ?string $pluralLabel = 'Incoming Material Non Stainless Steel';
    protected static ?string $modelLabel = 'Incoming Material Non Stainless Steel';

    public static function form(Form $form): Form
    {
        $defaultParts = collect(config('incommingMaterialNonSS.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        $summaryParts = collect(config('summary.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        return $form
            ->schema([
                //
                Fieldset::make('')
                    ->schema([
                        self::selectInput('permintaan_pembelian_id', 'Permintaan Pembelian', 'permintaanPembelian', 'id')
                            ->required(),
                        self::textInput('no_po', 'No. PO'),
                        self::textInput('supplier', 'Supplier'),
                    ]),

                Fieldset::make('Tabel Kelengkapan Material')
                    ->relationship('detail')
                    ->schema([
                        TableRepeater::make('details')
                            ->label('')
                            ->schema([
                                self::textInput('part', 'Description')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                ButtonGroup::make('result')
                                    ->options([
                                        '1' => 'Pass',
                                        '0' => 'Fail',
                                    ])
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row')
                                    ->default('individual'),

                                self::textInput('remark', 'Remark')

                            ])
                            ->default($defaultParts)
                            ->columns(3)
                    ]),

                Fieldset::make('Summary')
                    ->relationship('summary')
                    ->schema([
                        TableRepeater::make('summary')
                            ->label('')
                            ->schema([
                                self::textInput('part', 'Summary')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('critical', 'Critical'),
                                self::textInput('major', 'Major'),
                                self::textInput('minor', 'Minor'),
                                self::textInput('total_acc', 'Total'),

                            ])
                            ->default($summaryParts)
                            ->columns(3)
                    ]),

                Fieldset::make('')
                    ->schema([
                        self::textInput('batch_no', 'Batch No'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('no_po', 'No PO'),
                self::textColumn('supplier', 'Supplier'),
                self::textColumn('batch_no', 'Batch No'),
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
                        ->url(fn($record) => self::getUrl('pdfIncommingMaterialNonSS', ['record' => $record->id])),
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
            'index' => Pages\ListIncommingMaterialNonSS::route('/'),
            'create' => Pages\CreateIncommingMaterialNonSS::route('/create'),
            'edit' => Pages\EditIncommingMaterialNonSS::route('/{record}/edit'),
            'pdfIncommingMaterialNonSS' => Pages\pdfIncommingMaterialNonSS::route('/{record}/pdfIncommingMaterialNonSS')
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
                    $path = SignatureUploader::handle($state, 'ttd_', 'Quality/IncommingMaterial/NonSS/Signatures');
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