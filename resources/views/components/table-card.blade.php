<div class="w-full bg-gray-200 rounded-lg mt-2">
    <div id="title" class="py-2 bg-green-500 text-white flex justify-between items-center rounded-t-lg">
        <div class="pl-2 w-[75%]">
            <h2 class="text-lg font-bold">{{ $table->game->name }}</h2>
            <h3 class="text-sm">Créateur : {{ $table->organizer->name }}</h3>
        </div>
        <div>
            <div class="flex justify-center">
                @can('edit_table', $table)
                    <button class="mr-2 h-6 w-6">
                        <a
                            href="{{ route('table.edit', $table->id) }}"
                            onclick="return confirm('Etes-vous certain de vouloir faire une mise à jour ?')"
                        >
                            <img src="{{ asset('img/edit.png')}}" class="h-6 w-6">
                        </a>
                    </button>
                @endcan
                @can('delete_table', $table)
                    <form action="{{ route('table.delete', $table->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="ml-2 h-6 w-6"
                            onclick="return confirm('Etes-vous certain de vouloir annuler cette table ?')"
                        >
                            <img src="{{ asset('img/delete.png')}}">
                        </button>
                    </form>
                @endcan
            </div>
            <h2 class="mt-4 pr-2 text-lg font-bold">{{ $table->start_hour }}</h2>
        </div>
    </div>
    <div id="main-body" class="mt-1 px-2 pb-4 flex w-full justify-between items-center sm:mx-auto sm:justify-between">
        <div class="px-2 w-[20%] sm:px-4">
            <h2 class="font-bold">Joueurs</h2>
            <h3 class="text-sm p-1 rounded-lg text-center cursor-pointer">
                {{ $table->users->count() }} / {{ $table->players_number }}
            </h3>
        </div>
        <div class="px-2 w-[65%]">
            @foreach($table->users as $user)
                <span>{{ $user->name }}, </span>
            @endforeach
        </div>
        <div class="pr-2">
            @if (collect($table->users)->pluck('name')->doesntContain(Auth::user()->name))
                <button>
                    <a
                        href="{{ route('table.subscribe', [$table->id, Auth::user()->id]) }}"
                        onclick="return confirm('Etes-vous certain de vouloir vous inscrire ?')"
                    >
                        <img src="{{ asset('img/add-user.png')}}" class="w-8 h-8">
                    </a>
                </button>
            @endif

            @if (collect($table->users)->pluck('name')->contains(Auth::user()->name))
                <button>
                    <a
                        href="{{ route('table.unsubscribe', [$table->id, Auth::user()->id]) }}"
                        onclick="return confirm('Etes-vous certain de vouloir vous desinscrire ?')"
                    >
                        <img src="{{ asset('img/delete-user.png') }}" class="w-8 h-8">
                    </a>
                </button>
            @endif
        </div>
    </div>
    @if($table->description)
        <div class="mx-4 pb-2">
            <h2>{{ $table->description }}</h2>
        </div>
    @endif
</div>

