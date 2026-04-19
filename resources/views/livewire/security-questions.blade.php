<x-filament::section :aside="true" heading="Preguntas de Seguridad" description="Configura tus preguntas para recuperar la cuenta.">
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="text-right">
            <x-filament::button type="submit" form="submit" class="align-right">
                Guardar Cambios
            </x-filament::button>
        </div>
    </form>
</x-filament::section>
