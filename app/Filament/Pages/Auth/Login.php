<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
            Action::make('recuperar_password')
                ->label('¿Olvidaste tu contraseña?')
                ->link()
                ->url(route('filament.admin.pages.recuperar-password')),
        ];
    }
}
