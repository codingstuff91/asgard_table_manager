<x-app-layout>
    <x-slot name="header" class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Session du {{ $day->date->format('d/m/Y') }}
        </h2>
        <button class="mt-2 bg-green-500 px-4 py-2 rounded-lg text-white">
            <a href="{{ route('table.create', $day->id) }}">Ouvrir une table</a>
        </button>
    </x-slot>

    <div class="w-full mt-2 mx-auto lg:max-w-7xl">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-1 rounded-lg mt-4 mx-auto">
            @foreach ($tables as $table)
                <div class="flex flex-col bg-white rounded-lg text-center shadow-lg lg:my-0 xs:my-4 xs:mx-2">
                    <h2 class="text-xl font-semibold w-full bg-blue-200 p-2 rounded-lg">{{ $table->game->name }}</h2>
                    <h3>Début : {{ $table->start_hour }}</h3>
                    <h3>Maitre jeu : {{ $table->organizer->name }}</h3>
                    <hr>

                    <h3 class="my-1 font-semibold">Joueurs inscrits :</h3>
                    <ul class="mt-2">
                        @foreach ($table->users as $user)
                            <li>{{ $user->name }}</li>
                        @endforeach
                    </ul>

                    <div>                          
                        @if (collect($table->users)->pluck('name')->doesntContain(Auth::user()->name))
                            <button class="relative bottom-0 mt-2 px-4 py-2 bg-lime-300 rounded-lg w-full">
                                <a href="{{ route('table.subscribe', [$table->id, Auth::user()->id]) }}">S'incrire</a>
                            </button>
                        @endif
                        @if (collect($table->users)->pluck('name')->contains(Auth::user()->name))
                            <button class="relative bottom-0 mt-2 px-4 py-2 bg-red-500 text-white rounded-lg w-full">
                                <a href="{{ route('table.unsubscribe', [$table->id, Auth::user()->id]) }}">Se désincrire</a>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>