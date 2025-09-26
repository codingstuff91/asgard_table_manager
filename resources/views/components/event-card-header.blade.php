<div class="py-2 {{ $event->workshop ? 'bg-green-500' : 'bg-slate-400' }} rounded-t-lg">
        <div class="px-4 flex justify-between items-center">
            <h2 class="text-lg font-bold">{{ $event->name }}</h2>
            <h2 class="text-lg font-bold">{{ $event->workshop }}</h2>
            <h2 class="text-lg font-bold">{{ $event->start_hour }}</h2>
        </div>
        <div class="px-4 flex justify-end items-center">
            @can('edit_event')
                <button class="mr-2 h-6 w-6">
                    <a
                        href="{{ route('event.edit', $event->id) }}"
                        onclick="return confirm('Etes-vous certain de vouloir éditer cet évènement ?')"
                    >
                        <img src="{{ asset('img/edit.png')}}" class="h-6 w-6">
                    </a>
                </button>
            @endcan

                @can('delete_event')
                    <form action="{{ route('event.destroy', $event->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="ml-2 h-6 w-6"
                            onclick="return confirm('Etes-vous certain de vouloir annuler cet évènement ?')"
                        >
                            <img src="{{ asset('img/delete.png')}}">
                        </button>
                    </form>
                @endcan
        </div>
    </div>

