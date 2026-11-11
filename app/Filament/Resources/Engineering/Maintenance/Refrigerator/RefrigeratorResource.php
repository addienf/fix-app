<?php

namespace App\Filament\Resources\Engineering\Maintenance\Refrigerator;

use App\Filament\Resources\Engineering\Maintenance\Refrigerator\RefrigeratorResource\Pages;
use App\Filament\Resources\Engineering\Maintenance\Refrigerator\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\Refrigerator\Traits\TabelChecklist;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Traits\HasSignature;
// use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ButtonAction;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Str;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class RefrigeratorResource extends Resource
{
    use Informasi, TabelChecklist, HasSignature;
    protected static ?string $model = Refrigerator::class;
    protected static ?int $navigationSort = 25;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Refrigerator';
    protected static ?string $pluralLabel = 'Refrigerator';
    protected static ?string $modelLabel = 'Refrigerator';
    protected static ?string $slug = 'engineering/refrigerator';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                //
                Hidden::make('status_penyetujuan')
                    ->default('Belum Disetujui'),

                self::getInformasiSection($form),

                self::getTabelChecklistSection(),

                self::getRemarksSection(),

                Section::make('Customer Info')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Section::make('PIC')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ])
                                    ->schema([
                                        self::textInput('checked_name', 'Checked By')
                                            ->required(),

                                        self::dateInput('checked_date', 'Tanggal')
                                            ->required(),

                                        self::signatureInput(
                                            "checked_signature",
                                            '',
                                            'Engineering/Maintenance/Refrigerator/Signature'
                                        )
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                            ])
                    ])->hiddenOn('edit'),

                self::customerSignatureSection('Engineering/Maintenance/Refrigerator/Signature'),

                self::signatureLinkSection('refrigerator'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spkService.no_spk_service', 'No SPK Service'),
                self::textColumn('spkService.perusahaan', 'Nama Perusahaan'),
                self::textColumn('tag_no', 'Name/TAG No'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit Data Spesifikasi')
                        ->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label('Lihat PDF')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        // ->visible(fn($record) => $record->status_penyetujuan === 'Disetujui')
                        ->url(fn($record) => route('pdf.MaintenanceRefrigator', ['record' => $record->id])),
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
            'index' => Pages\ListRefrigerators::route('/'),
            'create' => Pages\CreateRefrigerator::route('/create'),
            'edit' => Pages\EditRefrigerator::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spkService',
                'detail',
                'pic'
            ]);
    }

    private static function getIsStock()
    {
        return
            ButtonGroup::make('ttd_method')
            ->label('Metode Tanda Tangan')
            ->options([
                'draw' => 'Tanda tangan langsung',
                'upload' => 'Upload scan tanda tangan',
            ])
            ->default('draw')
            ->live()
            ->dehydrated(false)
            ->required()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }
}
