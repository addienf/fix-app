<?php

namespace App\Filament\Resources\Quality\Defect;

use App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages;
use App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages\pdfDefectStatus;
use App\Filament\Resources\Quality\Defect\DefectStatusResource\RelationManagers;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
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
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class DefectStatusResource extends Resource
{
    protected static ?string $model = DefectStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Defect Status';
    protected static ?string $pluralLabel = 'Defect Status';
    protected static ?string $modelLabel = 'Defect Status';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Fieldset::make('')
                    ->schema([
                        self::Morp()
                    ]),
                Section::make('Chamber Identification')
                    ->collapsible()
                    ->schema([
                        self::textInput('no_spk', 'No SPK')->disabled(),
                        self::textInput('spk_marketing_id', 'ID SPK')
                            ->dehydrated()
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
                        self::textInput('tipe', 'Type/Model'),
                        self::textInput('volume', 'Volume'),
                        self::textInput('serial_number', 'S/N'),
                    ])->columns(6),
                // Fieldset::make('')
                //     // ->relationship('detail')
                //     ->schema([
                //         Repeater::make('details')
                //             ->schema([
                //                 self::textInput('mainPart', 'Main Part')
                //                     ->required(false)
                //                     ->extraAttributes([
                //                         'readonly' => true,
                //                         'style' => 'pointer-events: none;'
                //                     ]),
                //                 TableRepeater::make('parts')
                //                     ->label('')
                //                     ->schema([
                //                         self::textInput('part', 'Part')
                //                             ->extraAttributes([
                //                                 'readonly' => true,
                //                                 'style' => 'pointer-events: none;'
                //                             ]),
                //                         ButtonGroup::make('result')
                //                             ->options([
                //                                 '1' => 'Yes',
                //                                 '0' => 'No',
                //                             ])
                //                             ->onColor('primary')
                //                             ->offColor('gray')
                //                             ->gridDirection('row')
                //                             ->default('individual'),
                //                         Select::make('status')
                //                             ->label('Status')
                //                             ->options([
                //                                 'ok' => 'OK',
                //                                 'h' => 'Hold',
                //                                 'r' => 'Repaired',
                //                             ])
                //                             ->required(),
                //                     ])
                //                     ->addable(false)
                //                     ->deletable(false)
                //                     ->reorderable(false),
                //             ])
                //             ->columnSpanFull()
                //             ->addable(false)
                //             ->deletable(false)
                //             ->reorderable(false)
                //     ]),
                Fieldset::make('')
                    ->schema([
                        Textarea::make('note')
                            ->required()
                            ->label('Note')
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
                self::textColumn('defectable_type', 'Jenis')
                    ->getStateUsing(function ($record) {
                        if ($record->defectable_type == 'App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS') {
                            return 'Material Stanless Steel';
                        } else {
                            return 'Material Electrical';
                        }
                    }),
                self::textColumn('tipe', 'Tipe'),
                self::textColumn('volume', 'Volume'),
                self::textColumn('serial_number', 'S/N'),
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
                        ->url(fn($record) => self::getUrl('pdfDefectStatus', ['record' => $record->id])),
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
            'index' => Pages\ListDefectStatuses::route('/'),
            'create' => Pages\CreateDefectStatus::route('/create'),
            'edit' => Pages\EditDefectStatus::route('/{record}/edit'),
            'pdfDefectStatus' => pdfDefectStatus::route('/{record}/pdfDefectStatus')
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Quality/DefectStatus/Signatures');
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

    protected static function Morp(): MorphToSelect
    {
        return
            MorphToSelect::make('defectable')
            ->types([
                Type::make(PengecekanMaterialSS::class)
                    ->label('Pengecekan Material SS')
                    ->titleAttribute('spk_marketing_id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->spk?->no_spk ?? 'SPK Tidak Ditemukan'),
                Type::make(PengecekanMaterialElectrical::class)
                    ->label('Pengecekan Material Elektrikal')
                    ->titleAttribute('spk_marketing_id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->spk?->no_spk ?? 'SPK Tidak Ditemukan'),
            ])
            ->reactive()
            ->columnSpanFull()
            ->required()
            ->afterStateUpdated(function ($state, callable $set, $get) {
                if (! is_array($state)) return;

                $modelClass = $state['defectable_type'] ?? null;
                $modelId = $state['defectable_id'] ?? null;

                if ($modelClass) {

                    $model = $modelClass::find($modelId);

                    if ($model?->spk_marketing_id) {

                        $set('modelClass', $modelClass);
                        $set('spk_marketing_id', $model->spk_marketing_id);
                        $set('no_spk', $model->spk?->no_spk);

                        // Ambil semua details
                        $details = $model->detail?->details ?? [];

                        // Filter bagian yang result-nya "0"
                        $filtered = collect($details)->map(function ($item) {
                            $filteredParts = collect($item['parts'])->filter(fn($part) => $part['result'] === "0")->values()->all();

                            if (count($filteredParts)) {
                                return [
                                    'mainPart' => $item['mainPart'],
                                    'parts' => $filteredParts,
                                ];
                            }

                            return null;
                        })->filter()->values()->all(); // Reset key biar arraynya rapih

                        // Set hasil filtered ke form
                        $set('details', $filtered);
                    }
                }
            });
    }
}
