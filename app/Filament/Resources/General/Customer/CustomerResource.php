<?php

namespace App\Filament\Resources\General\Customer;

use App\Filament\Resources\General\Customer\CustomerResource\Pages;
use App\Models\General\Company;
use App\Models\General\Customer;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    use SimpleFormResource;
    protected static ?string $model = Customer::class;
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationGroup = 'General';
    protected static ?string $navigationLabel = 'Customer';
    protected static ?string $pluralLabel = 'Customer';
    protected static ?string $modelLabel = 'Customer';
    protected static ?string $slug = 'general/customer';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Customer')
                    ->collapsible()
                    ->schema([
                        self::select(),

                        self::textInput('name', 'Nama Customer'),

                        // PhoneInput::make('phone_number')
                        //     ->reactive()
                        //     ->label('Nomor Telpon')
                        //     ->required(),

                        self::textInput('department', 'Department'),
                    ])
                    ->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('name', 'Nama Customer'),
                self::textColumn('department', 'Department'),
                self::textColumn('company.name', 'Nama Perusahaan'),
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

    private static function select()
    {
        return
            Select::make(name: 'company_id')
            ->label('Company')
            ->placeholder('Pilih Data Company')
            ->searchable()
            ->reactive()
            ->getSearchResultsUsing(function (string $search) {
                return Company::query()
                    ->where('name', 'like', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('name', 'id');
            })
            ->options(function () {
                return Company::query()
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('name', 'id');
            })
            ->native(false)
            ->preload(false)
            ->required();
    }
}
