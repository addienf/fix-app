<?php

namespace App\Filament\Resources\General\URS;

use App\Filament\Resources\General\URS\URSResource\Pages;
use App\Filament\Resources\General\URS\URSResource\RelationManagers;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use \App\Models\Sales\URS;
use Filament\Forms;
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

class URSResource extends Resource
{
    protected static ?string $model = URS::class;
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationGroup = 'General';
    protected static ?string $navigationLabel = 'Penomoran URS';
    protected static ?string $pluralLabel = 'Penomoran URS';
    protected static ?string $modelLabel = 'Penomoran URS';
    protected static ?string $slug = 'general/penomoran-urs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Data URS')
                    ->collapsible()
                    ->schema([

                        self::textInput('no_urs', 'Nomor URS')
                            ->hint('Format: XXX/QKS/MKT/URS/MM/YY')
                            ->unique(),

                        self::selectInput('customer_id', 'Nama Customer', 'customer', 'name')
                            ->createOptionForm(fn() => self::customerFormSchema()),

                        Textarea::make('permintaan_khusus')
                            ->label('Remark Permintaan Khusus')
                            ->required()
                            ->columnSpanFull()

                    ])
                    ->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('no_urs', 'No URS'),
                self::textColumn('customer.name', 'Nama Customer'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListURS::route('/'),
            'create' => Pages\CreateURS::route('/create'),
            'edit' => Pages\EditURS::route('/{record}/edit'),
        ];
    }

    protected static function textColumn(string $fieldName, string $label): TextColumn
    {
        return
            TextColumn::make($fieldName)
            ->label($label)
            ->searchable()
            ->sortable();
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
            ->required();
    }
}
