<?php

namespace App\Filament\Resources\Engineering\Service;

use App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;
use App\Filament\Resources\Engineering\Service\Traits\Checklist;
use App\Filament\Resources\Engineering\Service\Traits\DataComplain;
use App\Filament\Resources\Engineering\Service\Traits\DetailProduk;
use App\Filament\Resources\Engineering\Service\Traits\InformasiProduk;
use App\Filament\Resources\Engineering\Service\Traits\InformasiUmum;
use App\Models\Engineering\Service\ServiceReport;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceReportResource extends Resource
{
    use InformasiUmum, DataComplain, InformasiProduk, Checklist, DetailProduk, HasSignature;
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

                self::getInformasiUmumSection($form),

                self::getDataComplainSection(),

                self::getInformasiProdukSection(),

                self::getChecklistSection(),

                self::getDetailProdukSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'checked',
                            'role' => 'Service By',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'approved',
                            'role' => 'Approved By',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->approved_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Engineering/ServiceReport/Signature'
                ),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spkService',
                'details',
                'produkServices',
                'pic',
            ]);
    }
}
