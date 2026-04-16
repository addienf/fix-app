<?php

namespace App\Filament\Resources\Engineering\Berita;

use App\Filament\Resources\Engineering\Berita\BeritaAcaraResource\Pages;
use App\Filament\Resources\Engineering\Berita\Traits\DetailPekerjaan;
use App\Filament\Resources\Engineering\Berita\Traits\InformasiBio;
use App\Filament\Resources\Engineering\Berita\Traits\InformasiUmum;
use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ButtonAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class BeritaAcaraResource extends Resource
{
    use InformasiUmum, InformasiBio, DetailPekerjaan, HasSignature;
    protected static ?string $model = BeritaAcara::class;
    protected static ?int $navigationSort = 30;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Berita Acara';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $pluralLabel = 'Berita Acara';
    protected static ?string $modelLabel = 'Berita Acara';
    protected static ?string $slug = 'engineering/berita-acara';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                self::getInformasiUmumSection($form),

                self::getInformasiBioSection(),

                self::getDetailPekerjaanSection(),

                Section::make('PIC')
                    ->collapsible()
                    ->hidden(function ($get) {
                        return filled($get('pic.pelanggan_ttd'));
                    })
                    ->relationship('pic')
                    ->schema([

                        self::textInput('jasa_name', 'Nama Penyedia Jasa')
                            ->required(false),

                        self::signatureInput('jasa_ttd', ''),
                    ]),

                Section::make('Pelanggan')
                    ->collapsible()
                    ->relationship('pic')
                    ->hidden(function ($get, $operation) {
                        return $operation === 'create'
                            || filled($get('pic.pelanggan_ttd'));
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data) {

                        if (!empty($data['pelanggan_ttd_draw'])) {
                            $data['pelanggan_ttd'] = $data['pelanggan_ttd_draw'];
                        }

                        if (!empty($data['pelanggan_ttd_upload'])) {
                            $data['pelanggan_ttd'] = $data['pelanggan_ttd_upload'];
                        }

                        unset($data['pelanggan_ttd_draw']);
                        unset($data['pelanggan_ttd_upload']);

                        return $data;
                    })
                    ->schema([

                        Grid::make(2)
                            ->schema([
                                self::textInput('pelanggan_name', 'Nama Pelanggan')
                                    ->required(),

                                self::getIsStock(),
                            ]),

                        self::signatureInput('pelanggan_ttd_draw', '', 'Engineering/Berita/Penyedia')
                            ->visible(fn($get) => $get('ttd_method') === 'draw'),

                        FileUpload::make('pelanggan_ttd_upload')
                            ->label('Upload Scan Tanda Tangan')
                            ->image()
                            ->directory('Engineering/Berita/Pelanggan')
                            ->visible(fn($get) => $get('ttd_method') === 'upload'),
                    ]),

                Section::make('Link Tanda Tangan')
                    ->hidden(function ($get, $operation) {
                        return $operation === 'create'
                            || filled($get('pic.pelanggan_ttd'));
                    })
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Actions::make([
                                    ButtonAction::make('generate_link')
                                        ->label('Generate Link')
                                        ->icon('heroicon-o-link')
                                        ->action(function ($record) {
                                            $record->pic->update([
                                                'sign_token' => Str::random(8),
                                                'sign_token_expires_at' => now()->addDays(3),
                                            ]);
                                        }),
                                ]),

                                // Placeholder::make('sign_link')
                                //     ->label(false)
                                //     ->content(
                                //         fn($record) =>
                                //         $record->pic?->sign_token
                                //             ? url('/qlb/' . $record->pic->sign_token)
                                //             : 'Belum ada link dibuat'
                                //     ),

                                Placeholder::make('sign_link')
                                    ->label(false)
                                    ->content(function ($record) {
                                        return $record->pic?->sign_token
                                            ? route('signature.show', [
                                                'type' => 'berita-acara',
                                                'token' => $record->pic->sign_token,
                                            ])
                                            : 'Belum ada link dibuat';
                                    }),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('spkService.no_spk_service')
                    ->label('No SPK Service'),

                TextColumn::make('no_surat')
                    ->label('No Surat'),

                TextColumn::make('tanggal')
                    ->date('d F Y'),
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
                        ->after(function ($record) {
                            $spk = SPKService::find($record->spk_service_id);

                            if ($spk) {
                                $spk->increment('lama_pelaksanaan');
                            }
                        })
                        ->icon('heroicon-o-trash')
                        ->tooltip('Hapus Data'),
                    Action::make('pdf_view')
                        ->label(_('Lihat PDF'))
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->url(fn($record) => route('pdf.beritaAcara', ['record' => $record->id])),
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
            'index' => Pages\ListBeritaAcaras::route('/'),
            'create' => Pages\CreateBeritaAcara::route('/create'),
            'edit' => Pages\EditBeritaAcara::route('/{record}/edit'),
        ];
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
            // ->hidden(fn($record) => filled($record?->pelanggan_ttd))
            ->required()
            // ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }
}
