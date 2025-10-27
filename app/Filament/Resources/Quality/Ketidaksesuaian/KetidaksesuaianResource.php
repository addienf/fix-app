<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian;

use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\Pages;
use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\RelationManagers;
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Traits\HasSignature;
use App\Traits\SimpleFormResource;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KetidaksesuaianResource extends Resource
{
    use SimpleFormResource, HasSignature;
    protected static ?string $model = Ketidaksesuaian::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 19;
    protected static ?string $navigationGroup = 'Quality';
    protected static ?string $navigationLabel = 'Ketidaksesuaian Produk & Material';
    protected static ?string $pluralLabel = 'Ketidaksesuaian Produk & Material';
    protected static ?string $modelLabel = 'Ketidaksesuaian Produk & Material';
    protected static ?string $slug = 'quality/ketidaksesuaian-produk-dan-material';

    public static function form(Form $form): Form
    {
        $isEdit = $form->getOperation() === 'edit';

        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Belum Diterima'),

                self::selectInput('spk_marketing_id', 'No SPK', 'spk', 'no_spk')
                    ->hiddenOn('edit')
                    ->placeholder('Pilih Nomor SPK'),

                Section::make('A. Informasi Umum')
                    ->schema([
                        self::dateInput('tanggal', 'Tanggal'),
                        self::textInput('nama_perusahaan', 'Nama Perusahaan'),
                        self::textInput('department', 'Department'),
                        self::textInput('pelapor', 'Pelapor'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('B. Detail Ketidaksesuaian Produk & Material')
                    ->schema([
                        TableRepeater::make('details')
                            ->relationship('details')
                            ->label('')
                            ->schema([

                                self::textInput('nama_produk', 'Nama Produk'),
                                self::textInput('serial_number', 'Serial Number'),
                                self::textInput('ketidaksesuaian', 'ketidaksesuaian'),
                                self::textInput('jumlah', 'Jumlah')->numeric(),
                                self::textInput('satuan', 'Satuan'),
                                self::textInput('keterangan', 'Keterangan'),
                            ])
                            ->deletable(true)
                            ->addable(true)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('C. Syarat dan Ketentuan')
                    ->relationship('snk')
                    ->schema([
                        self::textareaInput('penyebab', '1. Penyebab Ketidaksesuaian')->rows(1),
                        self::textareaInput('tindakan_kolektif', '2. Tindakan Kolektif')->rows(1),
                        self::textareaInput('tindakan_pencegahan', '3. Penyebab Pencegahan')->rows(1),
                    ])->collapsible(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'pelapor',
                            'role' => 'Dilaporkan Oleh',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                        [
                            'prefix' => 'diterima',
                            'role' => 'Diterima Oleh',
                            'hideLogic' => fn($operation, $record) =>
                            $operation === 'create' || filled($record?->diterima_signature)
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Quality/Ketidaksesuaian/Signatures'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('tanggal', 'Tanggal')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d M Y'))
                    ->alignCenter(),

                self::textColumn('pelapor', 'Pelapor')->alignCenter(),

                self::textColumn('nama_perusahaan', 'Nama Perusahaan')->alignCenter(),

                self::textColumn('status', 'Status')
                    ->badge()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'Belum Diterima' => 'danger',
                            'Diterima' => 'success',
                            default => 'gray',
                        };
                    })
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
                        ->visible(fn($record) => $record->status === 'Diterima')
                        ->url(fn($record) => route('pdf.ketidaksesuaian', ['record' => $record->id])),
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
            'index' => Pages\ListKetidaksesuaians::route('/'),
            'create' => Pages\CreateKetidaksesuaian::route('/create'),
            'edit' => Pages\EditKetidaksesuaian::route('/{record}/edit'),
        ];
    }
}
