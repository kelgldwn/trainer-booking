{{-- resources/views/filament/client/pages/register.blade.php --}}
<x-filament::page>
    <form wire:submit.prevent="register" class="space-y-4">
        {{ $this->form }}
        <x-filament::button type="submit">Register</x-filament::button>
    </form>
</x-filament::page>
