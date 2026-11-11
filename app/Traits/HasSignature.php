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
    public static function signatureSection(array $signatures, string $title = 'PIC', ?string $uploadPath = null, bool $required = true): Section
    {
        return
            Section::make($title)
            ->collapsible()
            ->relationship('pic')
            ->schema([
                Grid::make(count($signatures))
                    ->schema(
                        collect($signatures)->map(function ($item) use ($uploadPath) {
                            $prefix = $item['prefix'];
                            $role = $item['role'];
                            $hideLogic = $item['hideLogic'] ?? null;

                            return
                                Grid::make(1)
                                ->schema([
                                    Hidden::make("{$prefix}_name")
                                        ->default(fn() => auth()->id())
                                        ->afterStateHydrated(function ($component) {
                                            $component->state(auth()->id());
                                        })
                                        ->dehydrated(fn($get) => filled($get("{$prefix}_signature"))),

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
                                                ->dehydrated(false)
                                                ->required(false)
                                                ->extraAttributes([
                                                    'readonly' => true,
                                                    'style' => 'pointer-events: none;',
                                                ]),

                                            DatePicker::make("{$prefix}_date")
                                                ->label('Tanggal')
                                                ->default(now())
                                                ->requiredWith("{$prefix}_signature")
                                                ->dehydrated(fn($get) => filled($get("{$prefix}_signature")))
                                        ]),

                                    self::signatureInput("{$prefix}_signature", '', $uploadPath)
                                        ->required(false)
                                        ->dehydrated(fn($state) => filled($state)),
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
        if (blank($token)) {
            return null;
        }

        $record = static::where('sign_token', $token)->first();
        if (! $record) {
            return null;
        }

        $config = $record->signatureConfig();
        $signatureField = $config['signature_field'];

        if (filled($record->$signatureField)) {
            return null;
        }

        if ($record->sign_token_expires_at && now()->gt($record->sign_token_expires_at)) {
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

            $ip = $request->ip();

            if ($ip === '127.0.0.1') {
                $ip = '127.0.0.1 (Local)';
            }

            $this->update([
                $config['name_field'] => $request->input($config['name_field']),
                $config['signature_field'] => $path,
                $config['date_field'] => now(),
                'signed_ip' => $ip,
            ]);
        });
    }

    public static function signatureLinkSection(
        string $type,
        string $signatureField = 'approved_signature'
    ): Section {
        return Section::make('Link Tanda Tangan')
            ->hidden(
                fn($get, $operation) =>
                $operation === 'create' || filled($get("pic.{$signatureField}"))
            )
            ->schema([
                Grid::make(1)->schema([
                    TextInput::make('signature_link')
                        ->label('')
                        ->readOnly()
                        ->dehydrated(false)
                        ->formatStateUsing(
                            fn($record) => $record?->pic?->sign_token
                                ? route('signature.show', [
                                    'type' => $type,
                                    'token' => $record->pic->sign_token,
                                ])
                                : null
                        )
                        ->placeholder('Belum ada link dibuat')
                        ->suffixActions([
                            \Filament\Forms\Components\Actions\Action::make('generate')
                                ->icon('heroicon-o-link')
                                ->tooltip('Generate Link')
                                ->action(function ($record, $set) use ($type) {
                                    $token = \Illuminate\Support\Str::random(32);

                                    $record->pic->update([
                                        'sign_token' => $token,
                                        'sign_token_expires_at' => now()->addDays(3),
                                    ]);

                                    $set(
                                        'signature_link',
                                        route('signature.show', [
                                            'type' => $type,
                                            'token' => $token,
                                        ])
                                    );
                                }),

                            \Filament\Forms\Components\Actions\Action::make('open')
                                ->icon('heroicon-o-arrow-top-right-on-square')
                                ->tooltip('Buka Link')
                                ->hidden(fn($record) => blank($record?->pic?->sign_token))
                                ->alpineClickHandler(function ($record) use ($type) {
                                    $url = route('signature.show', [
                                        'type' => $type,
                                        'token' => $record->pic->sign_token,
                                    ]);

                                    return "window.open('$url', '_blank')";
                                }),

                            \Filament\Forms\Components\Actions\Action::make('copy')
                                ->icon('heroicon-o-clipboard')
                                ->hidden(fn($record) => blank($record?->pic?->sign_token))
                                ->alpineClickHandler(
                                    fn($state) => "window.navigator.clipboard.writeText('$state'); \$tooltip('Copied to clipboard', { timeout: 1500 });"
                                ),
                        ]),
                ]),
            ]);
    }

    public static function customerSignatureSection(string $uploadPath): Section
    {
        return Section::make('Pelanggan')
            ->collapsible()
            ->relationship('pic')
            ->hidden(
                fn($get, $operation) =>
                $operation === 'create'
                    || filled($get('pic.approved_signature'))
                    || blank($get('pic.checked_signature'))
            )
            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                if (! empty($data['approved_signature_draw'])) {
                    $data['approved_signature'] = $data['approved_signature_draw'];
                }
                if (! empty($data['approved_signature_upload'])) {
                    $data['approved_signature'] = $data['approved_signature_upload'];
                }
                unset($data['approved_signature_draw'], $data['approved_signature_upload']);
                return $data;
            })
            ->schema([
                Grid::make(2)->schema([
                    \Wallo\FilamentSelectify\Components\ButtonGroup::make('ttd_method')
                        ->label('Metode Tanda Tangan')
                        ->options([
                            'draw' => 'Tanda tangan langsung',
                            'upload' => 'Upload scan tanda tangan',
                        ])
                        ->default('draw')
                        ->live()
                        ->dehydrated(false)
                        ->required(
                            fn($get) => filled($get('pic.checked_signature'))
                                && blank($get('pic.approved_signature'))
                        )
                        ->columnSpanFull()
                        ->onColor('primary')
                        ->offColor('gray')
                        ->gridDirection('row'),
                    TextInput::make('approved_name')
                        ->label('Nama Pelanggan')
                        ->maxLength(255)
                        ->required(
                            fn($get) => filled($get('pic.checked_signature'))
                                && blank($get('pic.approved_signature'))
                        ),
                    DatePicker::make('approved_date')
                        ->label('Tanggal')
                        ->default(now())
                        ->required(
                            fn($get) => filled($get('pic.checked_signature'))
                                && blank($get('pic.approved_signature'))
                        ),
                ]),
                self::signatureInput('approved_signature_draw', '', $uploadPath)
                    ->visible(fn($get) => $get('ttd_method') === 'draw')
                    ->required(fn($get) => filled($get('pic.checked_signature'))),
                \Filament\Forms\Components\FileUpload::make('approved_signature_upload')
                    ->label('Upload Scan Tanda Tangan')
                    ->image()
                    ->directory($uploadPath)
                    ->visible(fn($get) => $get('ttd_method') === 'upload')
                    ->required(fn($get) => filled($get('pic.checked_signature'))),
            ]);
    }
}
