<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits\Informasi;
use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits\TabelChecklist;
use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Traits\HasSignature;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Wallo\FilamentSelectify\Components\ButtonGroup;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ButtonAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Filament\Support\Icons\Heroicon;
use Webbingbrasil\FilamentCopyActions\Pages\Actions\CopyAction;

class WalkinChamberResource extends Resource
{
    use Informasi, TabelChecklist, HasSignature;
    protected static ?string $model = WalkinChamber::class;
    protected static ?int $navigationSort = 23;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Walk in Test Chamber';
    protected static ?string $pluralLabel = 'Walk in Test Chamber';
    protected static ?string $modelLabel = 'Walk in Test Chamber';
    protected static ?string $slug = 'engineering/walkin-test-chamber';
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

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                            'lg' => 2,
                        ])
                            ->schema([
                                self::textInput('checked_name', 'Checked By')
                                    ->required(false),

                                self::dateInput('checked_date', 'Tanggal')
                                    ->required(false),

                                self::signatureInput(
                                    "checked_signature",
                                    '',
                                    'Engineering/Maintenance/WalkinTestChamber/Signature'
                                )
                                    ->required(false)
                                    ->columnSpanFull(),
                            ])
                    ])->hiddenOn('edit'),

                self::customerSignatureSection('Engineering/Maintenance/WalkinTestChamber/Signature'),

                self::signatureLinkSection('walkin-test-chamber'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('spkService.no_spk_service', 'No SPK Service'),
                self::textColumn('spkService.perusahaan', 'Nama Perusahaan'),
                self::textColumn('tag_no', 'WTC Name/TAG No'),
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
                        ->url(fn($record) => route('pdf.walkInChamber', ['record' => $record->id])),
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
            'index' => Pages\ListWalkinChambers::route('/'),
            'create' => Pages\CreateWalkinChamber::route('/create'),
            'edit' => Pages\EditWalkinChamber::route('/{record}/edit'),
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
