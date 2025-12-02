<?php

namespace App\Filament\Pages\Auth;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class LoginCustom extends Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        // $this->getCloudflameFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getCloudflameFormComponent(): Component
    {
        return
            Turnstile::make('captcha')
            ->theme('auto')
            // ->appearance('execute')
            ->language('en-us')
            ->size('flexible');
    }
}
