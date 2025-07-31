<?php

namespace App\Filament\Resources\Engineering\Service;

use App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;
use App\Filament\Resources\Engineering\Service\ServiceReportResource\RelationManagers;
use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Service\ServiceReport;
use App\Models\Engineering\SPK\SPKService;
use App\Models\General\Product;
use App\Services\SignatureUploader;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
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

        $lastValue2 = ServiceReport::latest('form_no')->value('form_no');
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
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state)
                                    return;

                                $complain = Complain::with('spkService', 'details')->find($state);
                                if (!$complain)
                                    return;

                                $details = $complain->details->map(function ($detail) {
                                    return [
                                        'produk_name' => $detail->unit_name ?? '-',
                                        'type' => $detail?->tipe_model ?? '-',
                                        'status_warranty' => $detail?->status_warranty  ?? '-',
                                    ];
                                })->toArray();

                                $formNo = $complain->form_no;
                                $namaComplain = $complain->name_complain;
                                $companyName = $complain->company_name;
                                $alamat = $complain->spkService->alamat;
                                $number = $complain->phone_number;

                                $set('form_no', $formNo);
                                $set('name_complaint', $namaComplain);
                                $set('company_name', $companyName);
                                $set('address', $alamat);
                                $set('phone_number', $number);
                                $set('serviceProduk', $details);
                            }),
                    ])
                    ->hiddenOn(operations: 'edit')
                    ->columns(1),

                Section::make('Informasi Umum')
                    ->collapsible()
                    ->schema([
                        Grid::make($isEdit ? 1 : 2)
                            ->schema([
                                TextInput::make('form_no')
                                    ->label('Nomor Form')
                                    // ->default('SR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)))
                                    ->placeholder($lastValue2 ? "Data Terakhir : {$lastValue2}" : 'Data Belum Tersedia')
                                    ->hiddenOn('edit')
                                    ->unique(ignoreRecord: true)
                                    // ->columnSpanFull()
                                    ->required()
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                DatePicker::make('tanggal')
                                    ->required()
                            ])
                    ]),

                Section::make('Data Complain')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name_complaint')
                                    ->required()
                                    ->label('Who Complaint'),

                                TextInput::make('company_name')
                                    ->required()
                                    ->label('Company Name'),

                                TextInput::make('address')
                                    ->required()
                                    ->label('Address'),

                                PhoneInput::make('phone_number')
                                    // ->defaultCountry('US')
                                    ->label('Phone Number')
                                    ->required(),
                            ])
                    ]),

                Section::make('Informasi Produk')
                    ->collapsible()
                    ->schema([
                        Repeater::make('serviceProduk')
                            ->relationship('produkServices')
                            ->label('')
                            ->schema([
                                Select::make('produk_name')
                                    ->label('Pilih Produk')
                                    ->options(Product::pluck('name', 'name')) // key dan value sama
                                    ->searchable(),

                                TextInput::make('type')
                                    ->label('Type/Model')
                                    ->required(),

                                TextInput::make('serial_number')
                                    ->label('Nomor Seri')
                                    ->required(),

                                ButtonGroup::make('status_warranty')
                                    ->required()
                                    ->label('Status Warranty')
                                    ->gridDirection('row')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ])
                            ])
                            ->columnSpanFull()
                            ->columns(2)
                            ->addable(false)
                            ->reorderable(false)
                            ->deletable(false)
                    ])
                    ->columns(2),

                Fieldset::make('Checklist')
                    ->label('')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('service_category')
                                    ->multiple()
                                    ->options([
                                        'installation' => 'Installation',
                                        'maintenance' => 'Maintenance',
                                        'repair' => 'Repair',
                                        'consultation' => 'Consultation',
                                    ]),

                                Select::make('actions')
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
                                    ->multiple()
                                    ->options([
                                        'controlling' => 'Controlling',
                                        'air_cooling_system' => 'Air Cooling System',
                                        'logging_system' => 'Logging System',
                                        'server_computer' => 'Server Computer',
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
                    ]),

                Section::make('Detail Produk')
                    ->collapsible()
                    ->schema([
                        Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('remark')
                                            ->label('Remark')
                                            ->required(),

                                        ButtonGroup::make('service_status')
                                            ->required()
                                            ->label('Service Status')
                                            ->gridDirection('row')
                                            ->options([
                                                1 => 'Yes',
                                                0 => 'No',
                                            ]),

                                        Textarea::make('taken_item')
                                            ->label('Taken Item')
                                            ->columnSpanFull()
                                            ->required(),

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
                            ])
                            ->columnSpanFull()
                            ->columns(2)
                            ->addable(false)
                            ->reorderable(false)
                            ->deletable(false),
                    ]),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Grid::make(1)
                                    ->schema([
                                        Hidden::make('checked_name')
                                            ->default(fn() => auth()->id()),

                                        TextInput::make('checked_name_display')
                                            ->label('Service By')
                                            ->default(fn() => auth()->user()?->name)
                                            ->disabled(),

                                        // self::textInput('checked_name', 'Checked By'),
                                        self::signatureInput('checked_signature', ''),
                                        DatePicker::make('checked_date')
                                            ->label('')
                                            ->default(now())
                                            ->required()
                                    ])->hiddenOn(operations: 'edit'),

                                Grid::make(1)
                                    ->schema([
                                        Hidden::make('approved_name')
                                            ->default(fn() => auth()->id())
                                            ->dehydrated(true)
                                            ->afterStateHydrated(function ($component) {
                                                $component->state(auth()->id());
                                            }),

                                        TextInput::make('approved_name_display')
                                            ->label('Approved By')
                                            ->placeholder(fn() => auth()->user()?->name)
                                            ->disabled(),

                                        // self::textInput('approved_name', 'Approved By'),
                                        self::signatureInput('approved_signature', ''),
                                        DatePicker::make('approved_date')
                                            ->label('')
                                            ->required()
                                    ])->hiddenOn(operations: 'create'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('spkService.no_spk_service')
                    ->label('No SPK Service'),

                TextColumn::make('form_no')
                    ->label('Form No'),

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
                        ->url(fn($record) => route('pdf.serviceReport', ['record' => $record->id])),
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
