<?php

namespace App\Filament\Resources\General\URS;

use App\Filament\Resources\General\URS\URSResource\Pages;
use App\Models\General\Customer;
use \App\Models\Sales\URS;
use App\Traits\HasAutoNumber;
use App\Traits\HasSelectCache;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class URSResource extends Resource
{
    use SimpleFormResource, HasAutoNumber, HasSelectCache;
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

                        self::autoNumberField('no_urs', 'Nomor URS', [
                            'prefix' => 'QKS',
                            'section' => 'MKT',
                            'type' => 'URS',
                            'table' => 'urs',
                        ]),

                        self::select(),

                        self::textareaInput('permintaan_khusus', 'Remark Permintaan Khusus')
                    ])
                    ->columns(2)

            ]);
    }

    protected static function getTableQuery(): Builder
    {
        return URS::query()->with(['customer']);
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

    private static function select()
    {
        return
            Select::make('customer_id')
            ->label('Customer')
            ->placeholder('Pilih Data Customer')
            ->searchable()
            ->getSearchResultsUsing(function (string $search) {
                $key = Customer::$CACHE_PREFIXES['search_customer'] . md5($search);

                return Cache::rememberForever($key, function () use ($search) {
                    return Customer::query()
                        ->where('name', 'like', "%{$search}%")
                        ->orderBy('id', 'desc')
                        ->limit(10)
                        ->pluck('name', 'id');
                });
            })
            ->options(function () {
                $keySelect = Customer::$CACHE_KEYS['select_customer'];

                return Cache::rememberForever($keySelect, function () {
                    return Customer::query()
                        ->orderBy('id', 'desc')
                        ->limit(10)
                        ->pluck('name', 'id');
                });
            })
            ->native(false)
            ->preload(false)
            ->required();
    }
}
