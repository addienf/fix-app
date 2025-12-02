<?php

namespace App\Filament\Resources\General\Customer;

use App\Filament\Resources\General\Customer\CustomerResource\Pages;
use App\Models\General\Customer;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationGroup = 'General';
    protected static ?string $navigationLabel = 'Customer';
    protected static ?string $pluralLabel = 'Customer';
    protected static ?string $modelLabel = 'Customer';
    protected static ?string $slug = 'general/customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Customer')
                    ->collapsible()
                    ->schema([
                        self::textInput('name', 'Nama Customer')
                            ->columnSpanFull(),

                        PhoneInput::make('phone_number')
                            ->label('Nomor Telpon')
                            ->required(),

                        self::textInput('department', 'Department'),

                        self::textInput('company_name', 'Nama Perusahaan'),

                        self::textInput('company_address', 'Alamat Perusahaan'),

                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('name', 'Nama Customer'),
                self::textColumn('phone_number', 'No Telpon'),
                self::textColumn('department', 'Department'),
                self::textColumn('company_name', 'Nama Perusahaan'),
                self::textColumn('company_address', 'Alamat Perusahaan'),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
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
