<?php

namespace App\Filament\Resources\Production\SPK;

use App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;
use App\Filament\Resources\Production\SPK\SPKQualityResource\RelationManagers;
use App\Models\Production\SPK\SPKQuality;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class SPKQualityResource extends Resource
{
    protected static ?string $model = SPKQuality::class;
    protected static ?int $navigationSort = 13;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'SPK Quality';
    protected static ?string $pluralLabel = 'SPK Quality';
    protected static ?string $modelLabel = 'SPK Quality';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('no_spk', 'Nomor SPK Quality')
                                    ->unique()
                                    ->helperText('Format: XXX/QKS/MKT/SPK/MM/YY'),
                                self::selectInput('spk_marketing_id', 'No SPK Marketing', 'spk', 'no_spk')
                                    ->required(),
                                self::textInput('dari', 'Dari'),
                                self::textInput('kepada', 'Kepada'),
                            ]),
                    ]),
                // Section::make('Detail PIC')
                //     ->collapsible()
                //     ->relationship('pic')
                //     ->schema([
                //         Grid::make(2)
                //             ->schema([
                //                 self::textInput('create_name', 'PIC Pembuat'),
                //                 self::textInput('receive_name', 'PIC Penerima'),
                //                 self::signatureInput('create_signature', 'Dibuat Oleh'),
                //                 self::signatureInput('receive_signature', 'Diterima Oleh'),
                //             ]),
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spk.no_spk', 'No SPK Marketing'),
                self::textColumn('no_spk', 'No SPK QUality'),
                self::textColumn('dari', 'Dari'),
                self::textColumn('kepada', 'Kepada'),
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
                        ->url(fn($record) => self::getUrl('pdfSPKQuality', ['record' => $record->id])),
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
            'index' => Pages\ListSPKQualities::route('/'),
            'create' => Pages\CreateSPKQuality::route('/create'),
            'edit' => Pages\EditSPKQuality::route('/{record}/edit'),
            'pdfSPKQuality' => Pages\pdfSPKQuality::route('/{record}/pdfSPKQuality')
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
