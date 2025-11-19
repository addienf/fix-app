<?php

namespace App\Filament\Resources\Quality\Defect\Traits;

use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait TabelChecklist
{
    use SimpleFormResource;
    protected static function getDitolakSection($form): Section
    {
        $isCreate = $form->getOperation() === 'create';
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Tabel Checklist Ditolak')
            ->collapsed($isEdit ? true : false)
            ->collapsible()
            ->schema([
                Repeater::make('details')
                    ->label('')
                    ->schema([
                        self::spesifikasiDitolak(),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->columnSpanFull()
                    ->when(
                        $isCreate,
                        fn($component) => $component->relationship('details'),
                    )
            ])
            ->hiddenOn('create');
    }

    protected static function getDirevisiSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Tabel Checklist Revisi')
            ->collapsed($isEdit ? true : false)
            ->collapsible()
            ->schema([
                Repeater::make('details')
                    ->relationship('details')
                    ->label('')
                    ->schema([

                        self::spesifikasiRevisi(),

                        Hidden::make('spesifikasi_ditolak')
                            ->disabledOn('edit'),

                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->columnSpanFull()
            ]);
    }

    private static function spesifikasiDitolak(): Repeater
    {
        return
            Repeater::make('spesifikasi_ditolak')
            ->label('')
            ->schema([

                Grid::make(3)
                    ->schema([
                        TextInput::make('mainPart')
                            ->label('Main Parts')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        ButtonGroup::make('mainPart_result')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row'),

                        Select::make('mainPart_status')
                            ->label('Status')
                            ->options([
                                'ok' => 'OK',
                                'h' => 'Hold',
                                'r' => 'Repaired',
                            ])
                            ->required()
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
                    ]),

                TableRepeater::make('parts')
                    ->label('')
                    ->schema([

                        TextInput::make('part')
                            ->label('Part')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        ButtonGroup::make('result')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'ok' => 'OK',
                                'h' => 'Hold',
                                'r' => 'Repaired',
                            ])
                            ->required()
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false),

            ])
            ->addable(false)
            ->deletable(false)
            ->reorderable(false)
            ->columnSpanFull();
    }

    private static function spesifikasiRevisi(): Repeater
    {
        return
            Repeater::make('spesifikasi_revisi')
            ->label('')
            ->schema([

                Grid::make(3)
                    ->schema([
                        TextInput::make('mainPart')
                            ->label('Main Parts')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        ButtonGroup::make('mainPart_result')
                            ->label('Result')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row'),

                        Select::make('mainPart_status')
                            ->label('Status')
                            ->options([
                                'ok' => 'OK',
                                'h' => 'Hold',
                                'r' => 'Repaired',
                            ])
                            ->required(),
                    ]),

                TableRepeater::make('parts')
                    ->label('')
                    ->schema([

                        TextInput::make('part')
                            ->label('Part')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        ButtonGroup::make('result')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'ok' => 'OK',
                                'h' => 'Hold',
                                'r' => 'Repaired',
                            ])
                            ->required(),

                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false),

            ])
            ->addable(false)
            ->deletable(false)
            ->reorderable(false)
            ->columnSpanFull();
    }
}
