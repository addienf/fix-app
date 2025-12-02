<?php

namespace App\Traits;

use App\Services\SignatureUploader;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

trait HasSignature
{
    /**
     * Generate dynamic signature section.
     *
     * @param  array   $signatures   Daftar tanda tangan (prefix, role, hideLogic)
     * @param  string  $title        Judul section
     * @param  string|null $uploadPath Lokasi upload tanda tangan (optional)
     */
    public static function signatureSection(array $signatures, string $title = 'PIC', ?string $uploadPath = null): Section
    {
        return Section::make($title)
            ->collapsible()
            ->relationship('pic')
            ->schema([
                Grid::make(count($signatures))
                    ->schema(
                        collect($signatures)->map(function ($item) use ($uploadPath) {
                            $prefix = $item['prefix'];
                            $role = $item['role'];
                            $hideLogic = $item['hideLogic'] ?? null;

                            return Grid::make(1)
                                ->schema([
                                    Hidden::make("{$prefix}_name")
                                        ->default(fn() => auth()->id())
                                        ->dehydrated(true)
                                        ->afterStateHydrated(function ($component) {
                                            $component->state(auth()->id());
                                        }),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make("{$prefix}_name_placeholder")
                                                ->label($role)
                                                ->default(fn() => auth()->user()?->name)
                                                ->placeholder(fn() => auth()->user()?->name)
                                                ->extraAttributes([
                                                    'readonly' => true,
                                                    'style' => 'pointer-events: none;',
                                                ]),

                                            DatePicker::make("{$prefix}_date")
                                                ->label('Tanggal')
                                                ->default(now())
                                                ->required(),
                                        ]),

                                    // 👇 kirim $uploadPath ke helper
                                    self::signatureInput("{$prefix}_signature", '', $uploadPath),


                                ])
                                ->hidden($hideLogic ?? fn() => false);
                        })->toArray()
                    ),
            ]);
    }

    /**
     * Custom Signature Input with uploader
     */
    protected static function signatureInput(string $fieldName, string $labelName, ?string $uploadPath = null): SignaturePad
    {
        $uploadPath = $uploadPath ?? 'Quality/Signatures'; // default path

        return SignaturePad::make($fieldName)
            ->label($labelName)
            ->exportPenColor('#0118D8')
            ->helperText('*Harap tandatangan di tengah area yang disediakan.')
            ->afterStateUpdated(function ($state, $set) use ($fieldName, $uploadPath) {
                if (blank($state)) return;

                $path = SignatureUploader::handle($state, 'ttd_', $uploadPath);

                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
