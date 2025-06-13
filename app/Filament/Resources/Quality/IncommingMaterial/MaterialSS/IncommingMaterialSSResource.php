<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\Pages;
use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\RelationManagers;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
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
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class IncommingMaterialSSResource extends Resource
{
    protected static ?string $model = IncommingMaterialSS::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 16;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Incoming Material SS';
    protected static ?string $pluralLabel = 'Incoming Material Stainless Steel';
    protected static ?string $modelLabel = 'Incoming Material Stainless Steel';

    public static function form(Form $form): Form
    {
        $defaultParts = collect(config('incommingMaterialSS.procedures'))
            ->map(function ($item, $index) {
                $expected = config('incommingMaterialSS.expected')[$index] ?? null;
                return [
                    'procedures' => $item,
                    'expected' => $expected,
                ];
            })->toArray();

        return $form
            ->schema([
                //

                Card::make('')
                    ->schema([
                        self::selectInput('permintaan_pembelian_id', 'Permintaan Pembelian', 'permintaanPembelian', 'id')
                            ->required(),
                        self::textInput('no_po', 'No. PO'),
                        self::textInput('supplier', 'Supplier'),
                    ]),

                // Fieldset::make('Tabel Kelengkapan Material')
                //     ->relationship('detail')
                //     ->schema([
                //         TableRepeater::make('details')
                //             ->label('')
                //             ->schema([

                //                 self::textArea('procedures', 'Description'),
                //                 self::textArea('expected', 'Expected'),
                //                 self::textArea('actual_result', 'Actual Result')


                //             ])
                //             ->default($defaultParts)
                //             ->columns(3)
                //     ]),

                Card::make('')
                    ->schema([
                        self::textInput('remark', 'Remarks'),
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
                self::textColumn('remark', 'Remarks'),
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
                        ->url(fn($record) => self::getUrl('pdfIncommingMaterialSS', ['record' => $record->id])),
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
            'index' => Pages\ListIncommingMaterialSS::route('/'),
            'create' => Pages\CreateIncommingMaterialSS::route('/create'),
            'edit' => Pages\EditIncommingMaterialSS::route('/{record}/edit'),
            'pdfIncommingMaterialSS' => Pages\pdfIncommingMaterialSS::route('/{record}/pdfIncommingMaterialSS')
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
    }

    protected static function textArea(string $fieldName, string $label): Textarea
    {
        return Textarea::make($fieldName)
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
                    $path = SignatureUploader::handle($state, 'ttd_', 'Quality/IncommingMaterial/SS/Signatures');
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