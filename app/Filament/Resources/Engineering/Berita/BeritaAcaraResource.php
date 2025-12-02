<?php

namespace App\Filament\Resources\Engineering\Berita;

use App\Filament\Resources\Engineering\Berita\BeritaAcaraResource\Pages;
use App\Filament\Resources\Engineering\Berita\Traits\DetailPekerjaan;
use App\Filament\Resources\Engineering\Berita\Traits\InformasiBio;
use App\Filament\Resources\Engineering\Berita\Traits\InformasiUmum;
use App\Models\Engineering\Berita\BeritaAcara;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                self::getInformasiUmumSection(),

                self::getInformasiBioSection(),

                self::getDetailPekerjaanSection(),

                Section::make('PIC')
                    ->collapsible()
                    ->relationship('pic')
                    ->schema([

                        Hidden::make('jasa_name')
                            ->default(fn() => auth()->id()),

                        self::textInput('jasa_name_placeholder', 'Nama Penyedia Jasa')
                            ->default(fn() => auth()->user()?->name)
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        // TextInput::make('jasa_name')
                        //     ->required()
                        //     ->label('Nama Penyedia Jasa')
                        //     ->default(fn() => auth()->user()?->name),

                        self::signatureInput('jasa_ttd', ''),
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
}
