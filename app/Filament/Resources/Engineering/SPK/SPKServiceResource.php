<?php

namespace App\Filament\Resources\Engineering\SPK;

use App\Filament\Resources\Engineering\SPK\SPKServiceResource\Pages;
use App\Filament\Resources\Engineering\SPK\SPKServiceResource\RelationManagers;
use App\Filament\Resources\Engineering\SPK\Traits\DeskripsiPekerjaan;
use App\Filament\Resources\Engineering\SPK\Traits\InformasiUmum;
use App\Filament\Resources\Engineering\SPK\Traits\Pemeriksaan;
use App\Filament\Resources\Engineering\SPK\Traits\Petugas;
use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\SPK\SPKService;
use App\Services\SignatureUploader;
use App\Traits\HasSignature;
use Carbon\Carbon;
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
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class SPKServiceResource extends Resource
{
    use InformasiUmum, DeskripsiPekerjaan, Pemeriksaan, Petugas, HasSignature;
    protected static ?string $model = SPKService::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'SPK Service';
    protected static ?string $pluralLabel = 'SPK Service';
    protected static ?string $modelLabel = 'SPK Service';
    protected static ?string $slug = 'engineering/spk-service';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getNavigationBadge(): ?string
    {
        $count = SPKService::where('status_penyelesaian', '!=', 'Selesai')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        // $lastValue = SPKService::latest('no_spk_service')->value('no_spk_service');
        // $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status_penyelesaian')
                    ->default('Belum Diselesaikan'),

                self::getInformasiUmumSection($form),

                self::getDeskripsiPekerjaanSection(),

                self::getPetugasSection(),

                self::getPemeriksaanSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'dikonfirmasi',
                            'role' => 'Dikonfirmasi Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'diketahui',
                            'role' => 'Diketahui Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->diketahui_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Engineering/SPK/Signatures'
                ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('complain.form_no', 'No Compaint Form'),

                self::textColumn('no_spk_service', 'No SPK Service'),

                self::textColumn('tanggal', 'Tanggal')->date('d F Y'),

                self::textColumn('perusahaan', 'Nama Perusahaan'),

                TextColumn::make('pemeriksaanPersetujuan.status_pekerjaan')
                    ->label('Status Pengerjaan')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if (strtolower($state) === 'ya') {
                            return 'Selesai';
                        }

                        return 'Belum Selesai';
                    })
                    ->color(function ($state) {
                        return strtolower($state) === 'ya' ? 'success' : 'danger';
                    })
                    ->alignCenter(),

                TextColumn::make('status_penyelesaian')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Selesai' ? 'success' : 'danger'
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
                        ->visible(fn($record) => $record->status_penyelesaian === 'Selesai')
                        ->url(fn($record) => route('pdf.spkService', ['record' => $record->id])),
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
            'index' => Pages\ListSPKServices::route('/'),
            'create' => Pages\CreateSPKService::route('/create'),
            'edit' => Pages\EditSPKService::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'complain',
                'petugas',
                'pemeriksaanPersetujuan',
                'beritaAcara',
                'pic',
                'permintaanSparepart',
                'walkinChamber',
                'chamberR2',
                'refrigerator',
                'coldRoom',
                'rissing',
                'walkinG2',
                'chamberG2',
                'service',
            ]);
    }
}
