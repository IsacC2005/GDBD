<?php

namespace App\Livewire;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class SecurityQuestions extends MyProfileComponent
{
    protected string $view = 'livewire.security-questions';

    public ?array $data = [];

    public function mount()
    {
        // Cargamos los datos actuales del usuario (excepto las respuestas por seguridad)
        $this->form->fill([
            'pregunta1' => auth()->user()->pregunta1,
            'pregunta2' => auth()->user()->pregunta2,
            'pregunta3' => auth()->user()->pregunta3,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pregunta de seguridad 1')
                    ->schema([
                        Select::make('pregunta1')
                            ->label('Pregunta')
                            ->options(fn (callable $get) => $this->availableQuestions([$get('pregunta2'), $get('pregunta3')]))
                            ->live(),
                        TextInput::make('respuesta1')
                            ->label('Respuesta')
                            ->password()
                            ->revealable(),
                    ])->columns(2),

                Section::make('Pregunta de seguridad 2')
                    ->schema([
                        Select::make('pregunta2')
                            ->label('Pregunta')
                            ->options(fn (callable $get) => $this->availableQuestions([$get('pregunta1'), $get('pregunta3')]))
                            ->live(),
                        TextInput::make('respuesta2')
                            ->label('Respuesta')
                            ->password()
                            ->revealable(),
                    ])->columns(2),

                Section::make('Pregunta de seguridad 3')
                    ->schema([
                        Select::make('pregunta3')
                            ->label('Pregunta')
                            ->options(fn (callable $get) => $this->availableQuestions([$get('pregunta1'), $get('pregunta2')]))
                            ->live(),
                        TextInput::make('respuesta3')
                            ->label('Respuesta')
                            ->password()
                            ->revealable(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    private function availableQuestions(array $excluded): array
    {
        return collect($this->getQuestions())
            ->reject(fn ($_label, $key) => in_array($key, array_filter($excluded)))
            ->all();
    }

    public function submit()
    {
        $data = $this->form->getState();
        $user = auth()->user();

        // Solo actualizamos la respuesta si el usuario escribió algo (para no sobreescribir con nulo)
        $updateData = [
            // 'pregunta1' => $data['pregunta1'],
            // 'pregunta2' => $data['pregunta2'],
            // 'pregunta3' => $data['pregunta3'],
        ];

        if (! empty($data['respuesta1'])) {
            $updateData['pregunta1'] = $data['pregunta1'];
            $updateData['respuesta1'] = Hash::make($data['respuesta1']);
        }
        if (! empty($data['respuesta2'])) {
            $updateData['pregunta2'] = $data['pregunta2'];
            $updateData['respuesta2'] = Hash::make($data['respuesta2']);
        }
        if (! empty($data['respuesta3'])) {
            $updateData['pregunta3'] = $data['pregunta3'];
            $updateData['respuesta3'] = Hash::make($data['respuesta3']);
        }

        $user->update($updateData);
        $user->save();

        Notification::make()
            ->success()
            ->title('Preguntas de seguridad actualizadas.')
            ->send();
    }

    private function getQuestions(): array
    {
        return [
            'mascota' => '¿Nombre de tu primera mascota?',
            'escuela' => '¿Nombre de tu primera escuela?',
            'auto' => '¿Marca de tu primer coche?',
            'ciudad' => '¿Ciudad donde se conocieron tus padres?',
        ];
    }
}
