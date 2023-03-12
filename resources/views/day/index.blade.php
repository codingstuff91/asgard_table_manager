<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des sessions') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-primary-button class="mb-4">
                <a href="{{ route('days.create') }}">Créer nouvelle session</a>
            </x-primary-button>
            @foreach ($days as $day)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-2">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-2xl font-extrabold flex justify-between">
                        <a href="{{ route('days.show', $day->id) }}">{{ $day->date->format('d/m/Y')}}</a>
                        {{ $day->tables_count }} tables
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>