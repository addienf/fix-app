<?php

namespace App\Traits;

use App\Services\SignatureUploader;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            // ->reactive()
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

                                    // Grid::make(2)
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ])
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

    public static function signatureSection2(array $signatures, string $title = 'PIC', ?string $uploadPath = null): Section
    {
        return Section::make($title)
            ->collapsible()
            ->reactive()
            // ->relationship('pic')
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

                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ])
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

    public static function findValidToken(string $token)
    {
        $record = static::where('sign_token', $token)->firstOrFail();

        $config = $record->signatureConfig();
        $signatureField = $config['signature_field'];

        // jika sudah pernah sign
        if ($record->$signatureField) {
            return null;
        }

        // jika token expired
        if (
            $record->sign_token_expires_at &&
            now()->gt($record->sign_token_expires_at)
        ) {
            return null;
        }

        return $record;
    }

    /**
     * Simpan signature
     */
    public function saveSignature(Request $request): void
    {
        $config = $this->signatureConfig();

        DB::transaction(function () use ($request, $config) {
            $path = SignatureUploader::handle(
                $request->input($config['signature_field']),
                'signature_',
                $config['upload_path']
            );

            $ip = request()->header('X-Forwarded-For');

            if ($ip) {
                $ip = explode(',', $ip)[0];
            } else {
                $ip = request()->getClientIp();
            }

            if ($ip === '127.0.0.1') {
                $ip = '127.0.0.1 (Local)';
            }

            $this->update([
                $config['name_field'] => $request->input($config['name_field']),
                $config['signature_field'] => $path,
                $config['date_field'] => now(),
                'signed_ip' => $ip,

                // single use token
                'sign_token' => null,
                'sign_token_expires_at' => null,
            ]);
        });
    }
}
