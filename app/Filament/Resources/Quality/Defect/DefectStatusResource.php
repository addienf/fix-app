<?php

namespace App\Filament\Resources\Quality\Defect;

use App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages;
use App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages\pdfDefectStatus;
use App\Filament\Resources\Quality\Defect\Traits\ChamberIdentification;
use App\Filament\Resources\Quality\Defect\Traits\TabelChecklist;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Services\SignatureUploader;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
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
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class DefectStatusResource extends Resource
{
    use ChamberIdentification, TabelChecklist, HasSignature;
    protected static ?string $model = DefectStatus::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Defect Status';
    protected static ?string $pluralLabel = 'Defect Status';
    protected static ?string $modelLabel = 'Defect Status';
    protected static ?string $slug = 'quality/defect-status';

    public static function getNavigationBadge(): ?string
    {
        $count = DefectStatus::where('status_penyelesaian', '!=', 'Disetujui')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        // $isCreate = $form->getOperation() === 'create';
        // $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diterima'),

                self::getChamberIdentificationSection($form),

                self::getDitolakSection($form),

                self::getDirevisiSection($form),

                self::getNoteSection(),

                self::getFileUploadSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'inspected',
                            'role' => 'Inspected by',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'accepted',
                            'role' => 'Accepted by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->accepted_signature)
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Approved by',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || blank($record?->accepted_signature) || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/DefectStatus/Signatures'
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('tipe_sumber')
                    ->label('Jenis')
                    ->formatStateUsing(
                        fn($state) =>
                        $state === 'electrical' ? 'Material Electrical' : 'Stainless Steel'
                    ),

                self::textColumn('tipe', 'Tipe'),

                self::textColumn('volume', 'Volume'),

                self::textColumn('serial_number', 'S/N'),

                TextColumn::make('status_penyelesaian')
                    ->label('Status')
                    ->badge()
                    ->color(function ($record) {
                        $penyelesaian = $record->status_penyelesaian;
                        $persetujuan = $record->status_persetujuan;

                        if ($penyelesaian === 'Disetujui') {
                            return 'success';
                        }

                        if ($penyelesaian !== 'Diterima' && $persetujuan !== 'Disetujui') {
                            return 'danger';
                        }

                        return 'warning';
                    })
                    ->alignCenter(),

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
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        // ->url(fn($record) => route('pdf.defectStatus')),
                        ->url(fn($record) => route('pdf.defectStatus', ['record' => $record->id])),
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
            'index' => Pages\ListDefectStatuses::route('/'),
            'create' => Pages\CreateDefectStatus::route('/create'),
            'edit' => Pages\EditDefectStatus::route('/{record}/edit'),
            'pdfDefectStatus' => pdfDefectStatus::route('/{record}/pdfDefectStatus')
        ];
    }
}
