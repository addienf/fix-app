<?php

namespace App\Filament\Resources\Engineering\Complain;

use App\Filament\Resources\Engineering\Complain\ComplainResource\Pages;
use App\Filament\Resources\Engineering\Complain\ComplainResource\RelationManagers;
use App\Models\Engineering\Complain\Complain;
use App\Models\General\Product;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class ComplainResource extends Resource
{
    protected static ?string $model = Complain::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Complaint';
    protected static ?string $pluralLabel = 'Complaint';
    protected static ?string $modelLabel = 'Complaint';
    protected static ?string $slug = 'engineering/complaint';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        $lastValue2 = Complain::latest('form_no')->value('form_no');
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        Grid::make($isEdit ? 3 : 2)
                            ->schema([
                                TextInput::make('form_no')
                                    ->label('Nomor Form')
                                    ->default('SR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)))
                                    ->placeholder($lastValue2 ? "Data Terakhir : {$lastValue2}" : 'Data Belum Tersedia')
                                    ->hiddenOn('edit')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                DatePicker::make('tanggal')
                                    ->required(),

                                TextInput::make('dari')
                                    ->required(),

                                TextInput::make('kepada')
                                    ->placeholder('Engineering')
                                    ->required(),
                            ])
                    ]),

                Section::make('Data Complain')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name_complain')
                                    ->required()
                                    ->label('Who Complaint'),

                                TextInput::make('company_name')
                                    ->required()
                                    ->label('Company Name'),

                                TextInput::make('department')
                                    ->required()
                                    ->label('Department'),

                                PhoneInput::make('phone_number')
                                    ->label('Phone Number')
                                    ->required(),

                                TextInput::make('receive_by')
                                    ->required()
                                    ->label('Receive By')
                                    ->columnSpanFull(),
                            ])
                    ]),

                Section::make('Informasi Produk')
                    ->collapsible()
                    ->schema([
                        Repeater::make('Service Produk')
                            ->relationship('details')
                            ->label('')
                            ->schema([
                                Select::make('unit_name')
                                    ->label('Pilih Produk')
                                    ->options(Product::pluck('name', 'name'))
                                    ->searchable(),

                                TextInput::make('tipe_model')
                                    ->label('Type/Model')
                                    ->required(),

                                ButtonGroup::make('status_warranty')
                                    ->required()
                                    ->label('Status Warranty')
                                    ->gridDirection('row')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ]),

                                TextInput::make('field_category')
                                    ->label('Field Category')
                                    ->required(),

                                Textarea::make('deskripsi')
                                    ->columnSpanFull()
                                    ->required()
                            ])
                            ->columnSpanFull()
                            ->columns(2)
                            ->addable(false)
                            ->reorderable(false)
                            ->deletable(false)
                    ])
                    ->columns(2),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Hidden::make('reported_name')
                                    ->default(fn() => auth()->id()),

                                TextInput::make('reported_name_display')
                                    ->label('Reported By')
                                    ->default(fn() => auth()->user()?->name)
                                    ->disabled(),

                                self::signatureInput('reported_signature', ''),

                                DatePicker::make('reported_date')
                                    ->label('')
                                    ->default(now())
                                    ->required()
                            ]),
                    ])
                    ->hiddenOn('edit')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('form_no')
                    ->label('Form No'),

                TextColumn::make('dari')
                    ->label('Dari'),

                TextColumn::make('kepada')
                    ->label('Kepada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->url(fn($record) => route('pdf.CatatanPelanggan', ['record' => $record->id])),
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
            'index' => Pages\ListComplains::route('/'),
            'create' => Pages\CreateComplain::route('/create'),
            'edit' => Pages\EditComplain::route('/{record}/edit'),
        ];
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Engineering/Complaint/Signature');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
