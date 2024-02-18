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
                <div class="mt-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm flex justify-between items-center sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-lg font-extrabold ">
                        <a href="{{ route('days.show', $day->id) }}">{{ $day->fullDayName }} {{ $day->date->format('d/m/Y')}}</a>
                    </div>
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-lg font-extrabold">
                        <p>{{ $day->tables_count }} tables</p>
                        @if($day->events_count > 0)
                            <p>{{ $day->events_count }} évènement {{ $day->events_count > 1 ? 's' : '' }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
