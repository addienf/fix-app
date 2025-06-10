<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS;

use App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource\Pages;
use App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource\RelationManagers;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Suleymanozev\FilamentRadioButtonField\Forms\Components\RadioButton;
use Wallo\FilamentSelectify\Components\ButtonGroup;
use Wallo\FilamentSelectify\Components\ToggleButton;

class PengecekanMaterialSSResource extends Resource
{
    protected static ?string $model = PengecekanMaterialSS::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Pengecekan Material SS';
    protected static ?string $pluralLabel = 'Pengecekan Material Stainless Steel';
    protected static ?string $modelLabel = 'Pengecekan Material Stainless Steel';

    public static function form(Form $form): Form
    {
        $defaultParts = collect(config('pengecekanSS'))
            ->map(function ($group) {
                return [
                    'mainPart' => $group['mainPart'],
                    'parts' => collect($group['parts'])
                        ->map(fn($part) => ['part' => $part])
                        ->toArray(),
                ];
            })
            ->toArray();

        return $form
            ->schema([
                //
                Section::make('Chamber Identification')
                    ->collapsible()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                self::selectInput('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                                    ->required(),
                                self::textInput('tipe', 'Type/Model'),
                                self::textInput('ref_document', 'Ref Document'),
                            ]),
                    ]),
                Section::make('Tabel Kelengkapan Material')
                    ->collapsible()
                    ->relationship('detail')
                    ->schema([
                        Repeater::make('details')
                            ->default($defaultParts)
                            ->schema([
                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),
                                TableRepeater::make('parts')
                                    ->label('')
                                    ->schema([
                                        TextInput::make('part')
                                            ->label('Part')
                                            ->extraAttributes([
                                                'readonly' => true,
                                                'style' => 'pointer-events: none;'
                                            ]),
                                        ButtonGroup::make('result')
                                            ->options([
                                                '1' => 'Yes',
                                                '0' => 'No',
                                            ])
                                            ->onColor('primary')
                                            ->offColor('gray')
                                            ->gridDirection('row')
                                            ->default('individual'),
                                        Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'ok' => 'OK',
                                                'h' => 'Hold',
                                                'r' => 'Repaired',
                                            ])
                                            ->required(),
                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                    ]),
                Card::make('')
                    ->schema([
                        Textarea::make('note')
                            ->required()
                            ->label('Note')
                            ->columnSpanFull()
                    ]),
                // Section::make('PIC')
                //     ->relationship('pic')
                //     ->collapsible()
                //     ->schema([
                //         Grid::make(3)
                //             ->schema([
                //                 self::textInput('inspected_name', 'Inspected By')
                //                     ->required(),
                //                 self::textInput('accepted_name', 'Accepted By')
                //                     ->required(),
                //                 self::textInput('approved_name', 'Approved By')
                //                     ->required(),
                //                 self::signatureInput('inspected_signature', '')
                //                     ->required(),
                //                 self::signatureInput('accepted_signature', '')
                //                     ->required(),
                //                 self::signatureInput('approved_signature', '')
                //                     ->required(),
                //                 self::datePicker('inspected_date', '')
                //                     ->required(),
                //                 self::datePicker('accepted_date', '')
                //                     ->required(),
                //                 self::datePicker('approved_date', '')
                //                     ->required(),
                //             ])
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'NO SPK'),
                self::textColumn('tipe', 'Type/Model'),
                self::textColumn('ref_document', 'Ref Document'),
                ImageColumn::make('pic.inspected_signature')
                    ->width(150)
                    ->label('Inspected')
                    ->height(75),
                ImageColumn::make('pic.accepted_signature')
                    ->width(150)
                    ->label('Accepted')
                    ->height(75),
                ImageColumn::make('pic.approved_signature')
                    ->width(150)
                    ->label('Approved')
                    ->height(75),
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
                        ->url(fn($record) => self::getUrl('pdfPengecekanMaterialSS', ['record' => $record->id])),
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
            'index' => Pages\ListPengecekanMaterialSS::route('/'),
            'create' => Pages\CreatePengecekanMaterialSS::route('/create'),
            'edit' => Pages\EditPengecekanMaterialSS::route('/{record}/edit'),
            'pdfPengecekanMaterialSS' => Pages\pdfPengecekanMaterialSS::route('/{record}/pdfPengecekanMaterialSS')
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