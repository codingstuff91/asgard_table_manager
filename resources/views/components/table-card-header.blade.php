<div
    id="title"
    class="py-2 {{ $color }} flex justify-between items-center rounded-t-lg"
>
        <div class="pl-2 w-[75%]">
            <h2 class="text-xl font-bold text-black">{{ $table->game->name }}</h2>
            <h3 class="text-sm text-black">Créateur : {{ $table->organizer->name }}</h3>
        </div>
        <div class="flex flex-col items-center">
            <div class="flex justify-center mr-2">
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

