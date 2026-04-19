<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Http\Middleware\Authenticate;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Panel;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Locked;

class RecuperarPassword extends SimplePage
{
    use WithRateLimiting;

    protected static ?string $slug = 'recuperar-password';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    #[Locked]
    public ?int $userId = null;

    public int $paso = 1;

    /** @var array<string> */
    public array $preguntas = [];

    public static function getWithoutRouteMiddleware(Panel $panel): string|array
    {
        return [Authenticate::class];
    }

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required()
                    ->autofocus(),
            ])
            ->statePath('data');
    }

    public function preguntasForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('respuesta1')
                    ->label($this->preguntas[0] ?? 'Pregunta 1')
                    ->required(),
                TextInput::make('respuesta2')
                    ->label($this->preguntas[1] ?? 'Pregunta 2')
                    ->required(),
                TextInput::make('respuesta3')
                    ->label($this->preguntas[2] ?? 'Pregunta 3')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('password')
                    ->label('Nueva contraseña')
                    ->password()
                    ->revealable()
                    ->required()
                    ->minLength(8),
                TextInput::make('password_confirmation')
                    ->label('Confirmar contraseña')
                    ->password()
                    ->revealable()
                    ->required()
                    ->same('password'),
            ])
            ->statePath('data');
    }

    public function buscarUsuario(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return;
        }

        $data = $this->form->getState();
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! $user->pregunta1) {
            Notification::make()
                ->danger()
                ->title('No se encontró una cuenta con preguntas de seguridad configuradas.')
                ->send();

            return;
        }

        $this->userId = $user->id;
        $this->preguntas = [
            $this->mapearPregunta($user->pregunta1),
            $this->mapearPregunta($user->pregunta2),
            $this->mapearPregunta($user->pregunta3),
        ];

        $this->preguntasForm->fill();
        $this->paso = 2;
    }

    public function verificarRespuestas(): void
    {
        try {
            $this->rateLimit(3);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return;
        }

        $data = $this->preguntasForm->getState();
        $user = User::find($this->userId);

        if (
            ! $user ||
            ! Hash::check($data['respuesta1'], $user->respuesta1) ||
            ! Hash::check($data['respuesta2'], $user->respuesta2) ||
            ! Hash::check($data['respuesta3'], $user->respuesta3)
        ) {
            Notification::make()
                ->danger()
                ->title('Las respuestas no son correctas. Intenta de nuevo.')
                ->send();

            return;
        }

        $this->passwordForm->fill();
        $this->paso = 3;
    }

    public function cambiarPassword(): void
    {
        $data = $this->passwordForm->getState();
        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        $user->update(['password' => Hash::make($data['password'])]);

        Notification::make()
            ->success()
            ->title('Contraseña actualizada correctamente.')
            ->send();

        $this->redirect(Filament::getLoginUrl());
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('buscarUsuario')
                    ->footer([
                        Actions::make([$this->getBuscarAction()])
                            ->fullWidth(),
                    ])
                    ->visible(fn (): bool => $this->paso === 1),

                Form::make([EmbeddedSchema::make('preguntasForm')])
                    ->id('preguntasForm')
                    ->livewireSubmitHandler('verificarRespuestas')
                    ->footer([
                        Actions::make([$this->getVerificarAction()])
                            ->fullWidth(),
                    ])
                    ->visible(fn (): bool => $this->paso === 2),

                Form::make([EmbeddedSchema::make('passwordForm')])
                    ->id('passwordForm')
                    ->livewireSubmitHandler('cambiarPassword')
                    ->footer([
                        Actions::make([$this->getCambiarAction()])
                            ->fullWidth(),
                    ])
                    ->visible(fn (): bool => $this->paso === 3),
            ]);
    }

    public function getHeading(): string|Htmlable
    {
        return match ($this->paso) {
            2 => 'Verifica tu identidad',
            3 => 'Nueva contraseña',
            default => 'Recuperar contraseña',
        };
    }

    public function getSubheading(): string|Htmlable|null
    {
        return match ($this->paso) {
            2 => 'Responde correctamente las tres preguntas de seguridad.',
            3 => 'Elige una nueva contraseña segura.',
            default => 'Ingresa tu correo electrónico para buscar tu cuenta.',
        };
    }

    protected function getBuscarAction(): Action
    {
        return Action::make('buscar')
            ->label('Buscar cuenta')
            ->submit('buscarUsuario');
    }

    protected function getVerificarAction(): Action
    {
        return Action::make('verificar')
            ->label('Verificar respuestas')
            ->submit('verificarRespuestas');
    }

    protected function getCambiarAction(): Action
    {
        return Action::make('cambiar')
            ->label('Cambiar contraseña')
            ->submit('cambiarPassword');
    }

    private function mapearPregunta(string $clave): string
    {
        return match ($clave) {
            'mascota' => '¿Nombre de tu primera mascota?',
            'escuela' => '¿Nombre de tu primera escuela?',
            'auto' => '¿Marca de tu primer coche?',
            'ciudad' => '¿Ciudad donde se conocieron tus padres?',
            default => $clave,
        };
    }
}
