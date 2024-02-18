<x-app-layout>
    <x-slot name="header" class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Session du {{ $day->date->format('d/m/Y') }}
        </h2>
        <button class="mt-2 bg-green-500 px-4 py-2 rounded-lg text-white">
            <a href="{{ route('table.create', $day->id) }}">Ouvrir une table</a>
        </button>
        <x-secondary-button>
            <a href="{{ route('event.create', $day->id) }}">Créer un événement</a>
        </x-secondary-button>
    </x-slot>

    <div class="w-full mt-2 mx-auto lg:max-w-7xl lg:px-4">
        @if(session('error'))
            <div class="text-center text-xl text-white font-semibold bg-red-500 w-full p-2 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        <div class="w-full rounded-lg pt-2 mx-auto w-full sm:grid sm:grid-cols-2 sm:gap-2 lg:grid-cols-3 lg:gap-2">
            @foreach ($tables as $table)
                <x-table-card :table="$table" />
            @endforeach
        </div>
    </div>
</x-app-layout>
