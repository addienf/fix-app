<?php

namespace App\Filament\Resources\Production\SPKVendor;

use App\Filament\Resources\Production\SPKVendor\SPKVendorResource\Pages;
use App\Filament\Resources\Production\SPKVendor\SPKVendorResource\RelationManagers;
use App\Models\Production\SPK\SPKVendor;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SPKVendorResource extends Resource
{
    protected static ?string $model = SPKVendor::class;
    protected static ?int $navigationSort = 13;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Production';
    protected static ?string $navigationLabel = 'SPK Vendor';
    protected static ?string $pluralLabel = 'SPK Vendor';
    protected static ?string $modelLabel = 'SPK Vendor';
    protected static ?string $slug = 'production/spk-vendor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        self::selectInput(),
                        TextInput::make('nama_perusahaan')
                            ->required()
                            ->label('Nama Perusahaan')
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('spk.no_spk')
                    ->label('No SPK Marketing'),
                TextColumn::make('nama_perusahaan'),
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
                        ->url(fn($record) => route('pdf.spkVendor', ['record' => $record->id])),
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
            'index' => Pages\ListSPKVendors::route('/'),
            'create' => Pages\CreateSPKVendor::route('/create'),
            'edit' => Pages\EditSPKVendor::route('/{record}/edit'),
        ];
    }

    protected static function selectInput(): Select
    {
        return
            Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            ->options(function () {
                return SPKMarketing::whereHas('permintaan')
                    ->whereDoesntHave('spkVendor')
                    ->pluck('no_spk', 'id');
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required();
    }
}
