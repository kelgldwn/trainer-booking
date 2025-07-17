<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <x-welcome />

                @php
                    $user = auth()->user();
                    if ($user->hasRole('admin')) {
                        $dashboardUrl = route('filament.admin.pages.dashboard');
                    } elseif ($user->hasRole('coach')) {
                        $dashboardUrl = route('filament.coach.pages.dashboard');
                    } elseif ($user->hasRole('client')) {
                        $dashboardUrl = route('filament.client.pages.dashboard');
                    } else {
                        $dashboardUrl = '#';
                    }
                @endphp

                <div class="mt-6">
                    <a href="{{ $dashboardUrl }}"
                        class="inline-block px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700">
                        Go to Filament Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>