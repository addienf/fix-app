<?php

namespace App\Filament\Resources\Sales\SPK;

use App\Filament\Resources\Sales\SPK\SPKResource\Pages;
use App\Filament\Resources\Sales\SPK\SPKResource\RelationManagers;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class SPKResource extends Resource
{
    protected static ?string $model = SPKMarketing::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'SPK Marketing';
    protected static ?string $pluralLabel = 'SPK Marketing';
    protected static ?string $modelLabel = 'SPK Marketing';

    public static function getSlug(): string
    {
        return 'spk-marketing';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Fieldset::make('Informasi Umum')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Rencana Pengiriman')
                            ->required()
                            ->displayFormat('M d Y'),
                        self::textInput('no_spk', 'Nomor SPK'),
                        Select::make('spesifikasi_product_id')
                            ->label('Customer')
                            ->required()
                            ->options(function () {
                                return SpesifikasiProduct::with('urs.customer') // load relasi sampai customer
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        $noUrs = $item->urs->no_urs ?? '-';
                                        $customerName = $item->urs->customer->name ?? '-';
                                        return [$item->id => "{$noUrs} - {$customerName}"];
                                    });
                            }),
                        self::textInput('dari', 'Dari'),
                        self::textInput('no_order', 'Nomor Order'),
                        self::textInput('kepada', 'Kepada'),
                    ]),
                Fieldset::make('Detail PIC')
                    ->relationship('pic')
                    ->schema([
                        self::textInput('create_name', 'PIC Pembuat'),
                        self::textInput('receive_name', 'PIC Penerima'),
                        self::signatureInput('create_signature', 'Dibuat Oleh'),
                        self::signatureInput('receive_signature', 'Diterima Oleh'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spesifikasiProduct.urs.no_urs', 'No URS'),
                self::textColumn('no_order', 'No Order'),
                ImageColumn::make('pic.create_signature')
                    ->width(150)
                    ->height(75),
                ImageColumn::make('pic.receive_signature')
                    ->width(150)
                    ->height(75),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('pdf_view')
                    ->label(_('View PDF'))
                    ->icon('heroicon-o-document')
                    ->color('success')
                    ->url(fn($record) => self::getUrl('pdfSPK', ['record' => $record->id])),
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
            'index' => Pages\ListSPKS::route('/'),
            'create' => Pages\CreateSPK::route('/create'),
            'edit' => Pages\EditSPK::route('/{record}/edit'),
            'pdfSPK' => Pages\pdfView::route('/{record}/pdfSPK')
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
                    $path = SignatureUploader::handle($state, 'ttd_', 'Sales/SPK/Signatures');
                    if ($path) {
                        $set($fieldName, $path);
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
}