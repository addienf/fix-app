<?php

namespace App\Filament\Resources\MR\Perubahan;

use App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource\Pages;
use App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource\RelationManagers;
use App\Filament\Resources\MR\Perubahan\Traits\DokumenPerubahan;
use App\Filament\Resources\MR\Perubahan\Traits\InformasiUmum;
use App\Filament\Resources\MR\Perubahan\Traits\PersetujuanPerubahan;
use App\Models\MR\Perubahan\PerubahanInformasi;

use Filament\Tables\Actions\ActionGroup;
use App\Traits\SimpleFormResource;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerubahanInformasiResource extends Resource
{
    use InformasiUmum, SimpleFormResource, DokumenPerubahan, PersetujuanPerubahan;

    protected static ?string $model = PerubahanInformasi::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'Management Representative';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Perubahan Informasi';
    protected static ?string $pluralLabel = 'Perubahan Informasi';
    protected static ?string $modelLabel = 'Perubahan Informasi';
    protected static ?string $slug = 'management-representative/perubahan-informasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Hidden::make('status')
                    ->default('Penanggung Jawab'),

                self::informasiUmum(),

                self::dokumenPerubahan(),

                self::persetujuanPerubahan()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                self::textColumn('nama', 'Nama Pemohon'),

                self::textColumn('tanggal', 'Tanggal')
                    ->date('d F Y'),

                self::textColumn('status', 'Status Tanda Tangan')
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Wakil Manajemen' ? 'success' : 'warning'
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
                        ->url(fn($record) => route('pdf.PerubahanInformasi', ['record' => $record->id])),
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
            'index' => Pages\ListPerubahanInformasis::route('/'),
            'create' => Pages\CreatePerubahanInformasi::route('/create'),
            'edit' => Pages\EditPerubahanInformasi::route('/{record}/edit'),
        ];
    }
}
