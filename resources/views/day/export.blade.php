<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Export des tables du {{ $day->date->format('d/m/Y') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                @foreach($tables as $table)
                    <p class="my-4">Table de <span class="font-bold">{{ $table->game->name }}</span>
                        à {{ $table->start_hour }}, proposée par {{ $table->organizer->name }}</p>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
