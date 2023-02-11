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
        <div class="grid grid-cols-2 gap-1 rounded-lg mt-4 mx-auto lg:grid-cols-5 lg:gap-2">
            @foreach ($tables as $table)
                <div class="flex flex-col bg-white rounded-lg text-center shadow-lg xs:my-4 xs:mx-2">
                    <h2 class="text-xl font-semibold w-full p-2 rounded-lg text-white {{ $table->game->category->color }}">{{ $table->game->name }}</h2>
                    
                    <h3 class="mt-2 font-bold text-lg">Début</h3>
                    <p>{{ $table->start_hour }}</p>
                    
                    <h3 class="mt-2 font-bold text-lg">
                        Maitre jeu
                    </h3>
                    <p class="mb-2">{{ $table->organizer->name }}</p>

                    <hr class="border border-gray-600">

                    <div class="bg-gray-200 pb-4">
                        <h3 class="mt-4 text-lg font-bold">Joueurs inscrits</h3>
                        <ul class="mt-4">
                            @foreach ($table->users as $user)
                                <li>
                                    @can('unsubscribe_user', $table)
                                        <button>
                                            <a href="{{ route('table.unsubscribe', [$table->id, $user->id]) }}">
                                                <i class="fa-solid fa-user-slash text-red-500 font-bold"></i>
                                            </a>
                                        </button>
                                    @endcan

                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>                          
                        @if (collect($table->users)->pluck('name')->doesntContain(Auth::user()->name))
                            <button class="relative bottom-0 px-4 py-2 bg-lime-300 w-full text-lg font-bold">
                                <a href="{{ route('table.subscribe', [$table->id, Auth::user()->id]) }}">S'incrire</a>
                            </button>
                        @endif
                        @if (collect($table->users)->pluck('name')->contains(Auth::user()->name))
                            <button class="relative bottom-0 px-4 py-2 bg-lime-300 w-full text-lg font-bold">
                                <a href="{{ route('table.unsubscribe', [$table->id, Auth::user()->id]) }}">Se désincrire</a>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>