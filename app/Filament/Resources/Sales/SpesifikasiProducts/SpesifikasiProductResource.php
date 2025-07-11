<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;
use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\RelationManagers;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class SpesifikasiProductResource extends Resource
{
    protected static ?string $model = SpesifikasiProduct::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'sales/spesifikasi-produk';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'Spesifikasi Produk';
    protected static ?string $pluralLabel = 'Spesifikasi Produk';
    protected static ?string $modelLabel = 'Spesifikasi Produk';

    public static function form(Form $form): Form
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            $form
            ->schema([
                Section::make('Informasi Umum')
                    ->schema([
                        self::selectInput('urs_id', 'No URS', 'urs', 'no_urs')
                            ->placeholder('Pilih Data URS')
                            ->hiddenOn('edit')
                            ->createOptionForm(fn() => self::ursFormSchema()),
                        self::textInput('delivery_address', 'Alamat Pengiriman'),
                        self::buttonGroup('is_stock', 'Untuk Stock ?'),
                    ])
                    ->columns($isEdit ? 2 : 3)
                    ->collapsible(),

                Section::make('Detail Spesifikasi Product')
                    ->schema([
                        Repeater::make('details')
                            ->label('')
                            ->relationship('details')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        self::selectInput('product_id', 'Pilih Produk', 'product', 'name')
                                            ->required(),
                                        self::textInput('quantity', 'Banyak Produk')
                                            ->numeric()
                                            ->required(),
                                    ]),
                                Grid::make()
                                    ->relationship('file')
                                    ->schema([
                                        FileUpload::make('file_path')
                                            ->label('File Pendukung')
                                            ->directory('Sales/Spesifikasi/Files')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->maxSize(10240)
                                            ->required()
                                            ->columnSpanFull()
                                            ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.'),
                                    ]),
                                TableRepeater::make('specification')
                                    ->label('Pilih Spesifikasi')
                                    ->schema([
                                        Select::make('name')
                                            ->reactive()
                                            ->required()
                                            ->label('')
                                            ->options(config('spec.spesifikasi'))
                                            ->columnSpan(1)
                                            ->placeholder('Pilih Jenis Spesifikasi'),
                                        ButtonGroup::make('value_bool')
                                            ->label('')
                                            ->required()
                                            ->options([
                                                '1' => 'Yes',
                                                '0' => 'No',
                                            ])
                                            ->onColor('primary')
                                            ->offColor('gray')
                                            ->gridDirection('row')
                                            ->visible(fn($get) => in_array($get('name'), ['Water Feeding System', 'Software', 'Tensile Test', 'Compression Test', 'Torque Test', 'Digital', 'Computerised'])),

                                        TextInput::make('value_str')
                                            ->required()
                                            ->label('')
                                            ->placeholder('Masukkan Nilai')
                                            ->visible(fn($get) => !in_array($get('name'), ['Water Feeding System', 'Software', 'Tensile Test', 'Compression Test', 'Torque Test', 'Digital', 'Computerised']))
                                            ->columnSpan(1),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(1)
                                    ->columnSpanFull()
                                    ->addActionLabel('Tambah Spesifikasi'),
                            ])
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible()
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Data Detail Produk'),
                    ]),

                Section::make('Penjelasan Tambahan')
                    ->collapsible()
                    ->schema([
                        Textarea::make('detail_specification')
                            ->label('Detail Spesifikasi')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('PIC')
                    ->hiddenOn(operations: 'edit')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                self::textInput('name', 'Nama Pembuat')
                                    ->required(),
                                DatePicker::make('date')
                                    ->label('Tanggal Dibuat')
                                    ->required()
                                    ->displayFormat('M d Y'),
                                self::signatureInput('signature')
                                    ->label('Tanda Tangan')
                                    ->required()
                                    ->columnSpan(2),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('urs.no_urs', 'No URS'),
                self::textColumn('urs.customer.name', 'Nama Customer'),
                self::textColumn('is_stock', 'Status Stock')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Stock' : 'Not Stock';
                    })
                    ->color(function ($state) {
                        return $state ? 'danger' : 'success';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(null)
                        ->after(function ($record) {
                            Notification::make()
                                ->title('Spesifikasi Product deleted successfully')
                                ->body("The Spesifikasi Product \"{$record->urs->no_urs}\" has been permanently removed.")
                                ->danger()
                                ->send();
                        }),
                    Action::make('pdf_view')
                        ->label(_('View PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->openUrlInNewTab()
                        ->url(fn($record) => route('specProduct.preview', ['record' => $record->id])),
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
            'index' => Pages\ListSpesifikasiProducts::route('/'),
            'create' => Pages\CreateSpesifikasiProduct::route('/create'),
            'edit' => Pages\EditSpesifikasiProduct::route('/{record}/edit'),
            'pdfSpecProduct' => Pages\viewPDF::route('/{record}/pdfSpecProduct')
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
            ->required();
    }

    protected static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                1 => 'Yes',
                0 => 'No',
            ])
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->default('individual');
    }

    protected static function signatureInput(string $fieldName): SignaturePad
    {
        return
            SignaturePad::make($fieldName)
            ->label('')
            ->exportPenColor('#0118D8')
            ->helperText('*Harap Tandatangan di tengah area yang disediakan.')
            ->afterStateUpdated(function ($state, $set) {
                if (blank($state))
                    return;
                $path = SignatureUploader::handle($state, 'ttd_', 'Sales/Spesifikasi/Signatures');
                if ($path) {
                    $set('signature', $path);
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

    protected static function ursFormSchema(): array
    {
        return [
            self::textInput('no_urs', 'Nomor URS')
                ->helperText('Format: XXX/QKS/MKT/URS/MM/YY')
                ->unique(),
            self::selectInput('customer_id', 'Nama Customer', 'customer', 'name')
                ->createOptionForm(fn() => self::customerFormSchema()),
            Textarea::make('permintaan_khusus')->label('Remark Permintaan Khusus')
        ];
    }

    protected static function customerFormSchema(): array
    {
        return [
            self::textInput('name', 'Nama Customer'),
            self::textInput('phone_number', 'No Telpon'),
            self::textInput('department', 'Department'),
            self::textInput('company_name', 'Nama Perusahaan'),
            self::textInput('company_address', 'Alamat Perusahaan'),
        ];
    }
}
