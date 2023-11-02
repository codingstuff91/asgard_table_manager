<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 gap-2 rounded-lg mt-2 mx-auto lg:grid-cols-5 lg:gap-2">
                <x-stats-card title="Utilisateurs" :data="$users" image="{{ asset('img/user.png') }}"/>
                <x-stats-card title="Sessions" :data="$days" image="{{ asset('img/calendar.png') }}"/>
                <x-stats-card title="Tables" :data="$tables" image="{{ asset('img/game-table.png') }}"/>
            </div>
        </div>
    </div>
</x-app-layout>
