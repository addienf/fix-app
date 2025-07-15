<?php

namespace App\Filament\Resources\Engineering\Service;

use App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;
use App\Filament\Resources\Engineering\Service\ServiceReportResource\RelationManagers;
use App\Models\Engineering\Service\ServiceReport;
use App\Models\Engineering\SPK\SPKService;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
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
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class ServiceReportResource extends Resource
{
    protected static ?string $model = ServiceReport::class;
    protected static ?int $navigationSort = 29;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Service Report';
    protected static ?string $pluralLabel = 'Service Report';
    protected static ?string $modelLabel = 'Service Report';
    protected static ?string $slug = 'engineering/service-report';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    public static function getNavigationBadge(): ?string
    {
        $count = ServiceReport::where('status_penyetujuan', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        // $lastValue = Refrigerator::latest('tag_no')->value('tag_no');
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penyetujuan')
                    ->default('Belum Disetujui'),

                Fieldset::make('SPK Service')
                    ->label('')
                    ->schema([
                        Select::make('spk_service_id')
                            ->label('Nomor SPK Service')
                            ->options(function () {
                                return SPKService::where('status_penyelesaian', 'Selesai')
                                    ->whereDoesntHave('service')
                                    ->pluck('no_spk_service', 'id');
                            })
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->hiddenOn(operations: 'edit'),
                    ])
                    ->columns(1),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        TextInput::make('produk_name')
                            ->label('Nama Produk')
                            ->required(),

                        TextInput::make('type')
                            ->label('Type/Model')
                            ->required(),

                        TextInput::make('serial_number')
                            ->label('Nomor Seri')
                            ->required(),

                        // TextInput::make('status_warranty')
                        //     ->label('Status Warranty')
                        //     ->required(),

                        ButtonGroup::make('status_warranty')
                            ->required()
                            ->label('Status Warranty')
                            ->gridDirection('row')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                    ])
                    ->columns(2),

                Section::make('Detail Informasi')
                    ->relationship('detail')
                    ->collapsible()
                    ->schema([
                        Textarea::make('taken_item')
                            ->label('Taken Item')
                            ->required(),

                        ButtonGroup::make('status_service')
                            ->required()
                            ->label('Status Service')
                            ->gridDirection('row')
                            ->options([
                                1 => 'Finish',
                                0 => 'Not Finish',
                            ]),

                        Select::make('action')
                            ->required()
                            ->multiple()
                            ->options([
                                'cleaning' => 'Cleaning',
                                'installation' => 'Installation',
                                'repairing' => 'Repairing',
                                'maintenance' => 'Maintenance',
                                'replacing' => 'Replacing',
                                'other' => 'Other',
                            ]),

                        Select::make('service_fields')
                            ->label('Service Fields')
                            ->required()
                            ->multiple()
                            ->options([
                                'controlling' => 'Controlling',
                                'air_circulation_system' => 'Air Circulation System',
                                'logging_system' => 'Logging System',
                                'serve_computer' => 'Serve Computer',
                                'networking' => 'Networking',
                                'water_feeding_system' => 'Water Feeding System',
                                'cooling_system' => 'Cooling System',
                                'humidifier_system' => 'Humidifier System',
                                'communication_system' => 'Communication System',
                                'air_heating_system' => 'Air Heating System',
                                'software' => 'Software',
                                'other' => 'Other',
                            ]),
                    ]),

                Fieldset::make('Upload File')
                    ->label('')
                    ->relationship('detail')
                    ->schema([
                        FileUpload::make('upload_file')
                            ->label('Lampiran')
                            ->directory('Engineering/ServiceReport/Files')
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->helperText('*Hanya file gambar (PNG, JPG, JPEG) yang diperbolehkan. Maksimal ukuran 10 MB.')
                            ->multiple()
                            ->image()
                            ->downloadable()
                            ->reorderable()
                            ->maxSize(10240)
                            ->columnSpanFull()
                            ->required(),
                    ]),

                Fieldset::make('Remarks')
                    ->label('')
                    ->schema([
                        Textarea::make('remarks')
                            ->required()
                            ->columnSpanFull()
                    ]),

                // Section::make('PIC')
                //     ->collapsible()
                //     ->relationship('pic')
                //     ->schema([

                //         Grid::make(2)
                //             ->schema([

                //                 Grid::make(1)
                //                     ->schema([
                //                         self::textInput('checked_name', 'Checked By'),
                //                         self::signatureInput('checked_signature', ''),
                //                         DatePicker::make('checked_date')
                //                             ->label('')
                //                             ->required()
                //                     ])->hiddenOn(operations: 'edit'),

                //                 Grid::make(1)
                //                     ->schema([
                //                         self::textInput('approved_name', 'Approved By'),
                //                         self::signatureInput('approved_signature', ''),
                //                         DatePicker::make('approved_date')
                //                             ->label('')
                //                             ->required()
                //                     ])->hiddenOn(operations: 'create'),

                //             ]),

                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('produk_name')
                    ->label('Nama Produk'),

                TextColumn::make('status_penyetujuan')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Disetujui' ? 'success' : 'danger'
                    )
                    ->alignCenter(),
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
                        ->visible(fn($record) => $record->status_penyetujuan === 'Disetujui')
                    // ->url(fn($record) => route('pdf.MaintenanceRefrigator', ['record' => $record->id])),
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
            'index' => Pages\ListServiceReports::route('/'),
            'create' => Pages\CreateServiceReport::route('/create'),
            'edit' => Pages\EditServiceReport::route('/{record}/edit'),
        ];
    }

    protected static function textInput(string $fieldName, string $label): TextInput
    {
        return TextInput::make($fieldName)
            ->label($label)
            ->required()
            ->maxLength(255);
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
                $path = SignatureUploader::handle($state, 'ttd_', 'Engineering/ServiceReport/Signature');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
