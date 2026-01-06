<?php

namespace App\Filament\Resources\General\Company;

use App\Filament\Resources\General\Company\CompanyResource\Pages;
use App\Filament\Resources\General\Company\CompanyResource\RelationManagers;
use App\Models\General\Company;
use App\Traits\SimpleFormResource;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class CompanyResource extends Resource
{
    use SimpleFormResource;
    protected static ?string $model = Company::class;
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationGroup = 'General';
    protected static ?string $navigationLabel = 'Company';
    protected static ?string $pluralLabel = 'Company';
    protected static ?string $modelLabel = 'Company';
    protected static ?string $slug = 'general/company';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Customer')
                    ->collapsible()
                    ->schema([
                        self::textInput('name', 'Nama Company')
                            ->columnSpanFull(),

                        self::textInput('address', 'Alamat Company'),

                        PhoneInput::make('phone')
                            ->label('Nomor Telpon')
                            ->required(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('name', 'Nama Company'),
                self::textColumn('address', 'Alamat'),
                self::textColumn('phone', 'No Telpon'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
