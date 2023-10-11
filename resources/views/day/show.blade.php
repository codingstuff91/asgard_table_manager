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
        @if(session('error'))
            <div class="text-center text-xl text-white font-semibold bg-red-500 w-full p-2 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        <div class="grid grid-cols-2 gap-1 rounded-lg mt-4 mx-auto lg:grid-cols-5 lg:gap-2">
            @foreach ($tables as $table)
                <div class="flex flex-col bg-white rounded-lg text-center shadow-lg xs:my-4 xs:mx-2">
                    <h2 class="text-xl font-semibold w-full p-2 rounded-lg text-white {{ $table->game->category->color }}">{{ $table->game->name }}</h2>

                    <h3 class="mt-2 font-bold text-lg">Début</h3>
                    <p>{{ $table->start_hour }}</p>

                    <h3 class="mt-2 font-bold text-lg">
                        Organisateur
                    </h3>
                    <p class="mb-2">{{ $table->organizer->name }}</p>

                    <hr class="border border-gray-600">

                    <div class="bg-gray-200 pb-4">
                        <h3 class="mt-4 text-lg font-bold">Joueurs inscrits</h3>
                        <h4 class="mt-1 text-lg font-bold">{{ $table->users->count() }} / {{ $table->players_number }}</h4>
                        <ul class="mt-4">
                            @foreach ($table->users as $user)
                                <li>
                                    @can('unsubscribe_user', $table)
                                        <button>
                                            <a
                                                href="{{ route('table.unsubscribe', [$table->id, $user->id]) }}"
                                            >
                                                <i class="fa-solid fa-user-slash text-red-500 font-bold"></i>
                                            </a>
                                        </button>
                                    @endcan

                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex justify-center bg-gray-200">
                        @if (collect($table->users)->pluck('name')->doesntContain(Auth::user()->name))
                            <button class="relative bottom-0 px-4 py-2">
                                <a
                                    href="{{ route('table.subscribe', [$table->id, Auth::user()->id]) }}"
                                    onclick="return confirm('Etes vous sur de vouloir vous inscrire ?')"
                                >
                                    <img src="{{ asset('img/add-user.png')}}" class="w-8 h-8">
                                </a>
                            </button>
                        @endif
                        @if (collect($table->users)->pluck('name')->contains(Auth::user()->name))
                            <button class="relative bottom-0 px-4 py-2">
                                <a
                                    href="{{ route('table.unsubscribe', [$table->id, Auth::user()->id]) }}"
                                    onclick="return confirm('Etes vous certain de vouloir vous desinscrire ?')"
                                >
                                    <img src="{{ asset('img/delete-user.png') }}" class="w-8 h-8">
                                </a>
                            </button>
                        @endif
                            @can('delete_table', $table)
                                <form action="{{ route('table.delete', $table->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="relative bottom-0 px-4 py-2"
                                        onclick="return confirm('Etes-vous certain de vouloir la supprimer ?')"
                                    >
                                        <img src="{{ asset('img/delete.png')}}" class="h-8 w-8">
                                    </button>
                                </form>
                            @endcan
                            @can('edit_table', $table)
                                <button class="relative bottom-0 px-4 py-2">
                                    <a
                                        href="{{ route('table.edit', $table->id) }}"
                                        onclick="return confirm('Etes-vous certain de vouloir la mettre à jour ?')"
                                    >
                                        <img src="{{ asset('img/edit.png')}}" class="h-8 w-8">
                                    </a>
                                </button>
                            @endcan
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
