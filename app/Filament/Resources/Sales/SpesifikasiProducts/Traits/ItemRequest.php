<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\Traits;

use App\Models\General\Product;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait ItemRequest
{
    use SimpleFormResource;

    private const CATEGORY_CHAMBER = 1;
    private const CATEGORY_MECMESIN = 2;
    private const SPEC_BUTTON_GROUP = ['Water Feeding System', 'Software', 'Tipe Chamber'];

    protected static function itemRequestSection(): Section
    {
        return Section::make('Item Request')
            ->collapsible()
            ->schema([
                Repeater::make('details')
                    ->label('')
                    ->relationship('details')
                    ->mutateRelationshipDataBeforeFillUsing(fn(array $data) => static::fillChamberSpec($data))
                    ->mutateRelationshipDataBeforeCreateUsing(fn(array $data) => static::clearUnusedSpec($data))
                    ->mutateRelationshipDataBeforeSaveUsing(fn(array $data) => static::clearUnusedSpec($data))
                    ->schema([
                        static::productQuantityGrid(),
                        static::specFileGrid(),
                        static::chamberSpecRepeater(),
                        static::mecmesinSpecRepeater(),
                    ])
                    ->defaultItems(1)
                    ->reorderable()
                    ->collapsible()
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Data Detail Produk'),
            ]);
    }

    /* ------------------------------------------------------------------
     |  Komponen Form
     * ------------------------------------------------------------------ */
    protected static function productQuantityGrid(): Grid
    {
        return Grid::make(2)
            ->schema([
                Select::make('product_id')
                    ->label('Pilih Produk')
                    ->options(fn() => static::productOptions())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                self::textInput('quantity', 'Banyak Produk')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),
            ]);
    }
    protected static function specFileGrid(): Grid
    {
        return Grid::make()
            ->relationship('file')
            ->schema([
                self::uploadField(
                    'file_path',
                    'File Spesifikasi',
                    'Sales/Spesifikasi/Files',
                    'Hanya file PDF yang diperbolehkan (maks 10 MB).',
                    types: ['application/pdf'],
                    maxSize: 10240,
                ),
            ]);
    }
    protected static function chamberSpecRepeater(): TableRepeater
    {
        return TableRepeater::make('specification')
            ->label('Spesifikasi Chamber')
            ->visible(fn($get) => static::isCategory($get('product_id'), static::categoryChamber()))
            ->default(fn() => static::chamberSpecState())
            ->schema([
                TextInput::make('name')
                    ->label('Parameter')
                    ->readOnly()
                    ->required(),
                ButtonGroup::make('value_bool')
                    ->label('Nilai')
                    ->required()
                    ->options(fn($get) => static::chamberChoiceOptions($get('name')))
                    ->onColor('primary')
                    ->offColor('gray')
                    ->gridDirection('row')
                    ->visible(fn($get) => static::isChoiceSpec($get('name'))),
                TextInput::make('value_str')
                    ->label('Nilai')
                    ->required()
                    ->placeholder('Masukkan Nilai')
                    ->visible(fn($get) => ! static::isChoiceSpec($get('name'))),
            ])
            ->columns(2)
            ->addable(false)
            ->deletable(false)
            ->reorderable(false)
            ->columnSpanFull();
    }
    protected static function mecmesinSpecRepeater(): Repeater
    {
        return Repeater::make('specification_mecmesin')
            ->label('Spesifikasi Mecmesin')
            ->visible(fn($get) => static::isCategory($get('product_id'), static::categoryMecmesin()))
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('test_type')
                            ->label('Test Type')
                            ->options([
                                'tensile' => 'Tensile Test',
                                'compression' => 'Compression Test',
                                'torque' => 'Torque Test',
                            ])
                            ->required(),
                        ButtonGroup::make('jenis_tes')
                            ->label('Jenis Tes')
                            ->gridDirection('row')
                            ->options([
                                'digital' => 'Digital',
                                'computerised' => 'Computerised',
                            ])
                            ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        TextInput::make('capacity')
                            ->label('Capacity')
                            ->required(),
                        TextInput::make('sample')
                            ->label('Sample to test')
                            ->required(),
                    ]),
            ])
            ->defaultItems(1)
            ->minItems(1)
            ->addable(false)
            ->reorderable(false)
            ->columns(2)
            ->addActionLabel('Tambah Test');
    }
    /* ------------------------------------------------------------------
     |  Konfigurasi
     * ------------------------------------------------------------------ */
    protected static function categoryChamber(): int
    {
        return 1;
    }
    protected static function categoryMecmesin(): int
    {
        return 2;
    }
    protected static function chamberChoiceOptions(?string $name): array
    {
        return match ($name) {
            'Tipe Chamber' => ['knockdown' => 'Knockdown', 'regular' => 'Regular'],
            'Software' => ['with' => 'With Software', 'without' => 'Without Software'],
            'Water Feeding System' => ['1' => 'Yes', '0' => 'No'],
            default => [],
        };
    }
    protected static function isChoiceSpec(?string $name): bool
    {
        return static::chamberChoiceOptions($name) !== [];
    }
    /* ------------------------------------------------------------------
     |  Helper Data & Query
     * ------------------------------------------------------------------ */
    protected static function productOptions(): array
    {
        static $options;
        return $options ??= Product::orderBy('id')->pluck('name', 'id')->all();
    }
    protected static function productCategory(int|string|null $productId): ?int
    {
        static $cache = [];
        if (blank($productId)) {
            return null;
        }
        return $cache[$productId] ??= (int) Product::whereKey($productId)->value('category_id');
    }
    protected static function isCategory(int|string|null $productId, int $category): bool
    {
        return static::productCategory($productId) === $category;
    }
    /**
     * Susun spesifikasi chamber sesuai urutan config, isi dengan nilai lama jika ada.
     */
    protected static function chamberSpecState(?array $saved = []): array
    {
        $saved = collect($saved ?? [])->keyBy('name');
        return collect(array_keys(config('spec.spesifikasi', [])))
            ->map(fn(string $name) => [
                'name' => $name,
                'value_bool' => $saved[$name]['value_bool'] ?? null,
                'value_str' => $saved[$name]['value_str'] ?? null,
            ])
            ->all();
    }
    protected static function fillChamberSpec(array $data): array
    {
        if (static::isCategory($data['product_id'] ?? null, static::categoryChamber())) {
            $data['specification'] = static::chamberSpecState($data['specification'] ?? []);
        }
        return $data;
    }
    protected static function clearUnusedSpec(array $data): array
    {
        $productId = $data['product_id'] ?? null;
        if (! static::isCategory($productId, static::categoryChamber())) {
            $data['specification'] = null;
        }
        if (! static::isCategory($productId, static::categoryMecmesin())) {
            $data['specification_mecmesin'] = null;
        }
        return $data;
    }
}
