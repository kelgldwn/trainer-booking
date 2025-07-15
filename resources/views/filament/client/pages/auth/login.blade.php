<x-filament-panels::auth.page>
    <x-slot name="heading">
        {{ __('Login to Admin Panel') }}
    </x-slot>

    <form wire:submit.prevent="authenticate" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit" form="authenticate">
            {{ __('Login') }}
        </x-filament::button>

        <div class="text-center mt-4">
            <a href="{{ route('filament.client.pages.register') }}"
               class="text-sm text-primary-600 hover:underline">
                Don't have an account? Register here
            </a>
        </div>
    </form>
</x-filament-panels::auth.page>
