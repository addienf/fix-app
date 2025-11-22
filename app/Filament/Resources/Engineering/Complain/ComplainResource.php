<?php

namespace App\Filament\Resources\Engineering\Complain;

use App\Filament\Resources\Engineering\Complain\ComplainResource\Pages;
use App\Filament\Resources\Engineering\Complain\Traits\DataComplain;
use App\Filament\Resources\Engineering\Complain\Traits\InformasiProduk;
use App\Filament\Resources\Engineering\Complain\Traits\InformasiUmum;
use App\Models\Engineering\Complain\Complain;
use App\Traits\HasSignature;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ComplainResource extends Resource
{
    use InformasiUmum, DataComplain, InformasiProduk, HasSignature;
    protected static ?string $model = Complain::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Engineering';
    protected static ?string $navigationLabel = 'Complaint';
    protected static ?string $pluralLabel = 'Complaint';
    protected static ?string $modelLabel = 'Complaint';
    protected static ?string $slug = 'engineering/complaint';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                self::getInformasiUmumSection($form),

                self::getDataComplainSection(),

                self::getInformasiProdukSection(),

                static::signatureSection(
                    [
                        [
                            'prefix' => 'reported',
                            'role' => 'Reported By',
                            'hideLogic' => fn($operation) => $operation === 'edit',
                        ],
                    ],
                    title: 'PIC',
                    uploadPath: 'Engineering/Complaint/Signature'
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('form_no')
                    ->label('Form No'),

                TextColumn::make('dari')
                    ->label('Dari'),

                TextColumn::make('kepada')
                    ->label('Kepada'),
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
                        ->url(fn($record) => route('pdf.CatatanPelanggan', ['record' => $record->id])),
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
            'index' => Pages\ListComplains::route('/'),
            'create' => Pages\CreateComplain::route('/create'),
            'edit' => Pages\EditComplain::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'spkService',
                'details',
                'pic'
            ]);
    }
}
