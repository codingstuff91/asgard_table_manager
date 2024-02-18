<div id="main-body" class="mt-1 px-2 pb-4 flex w-full justify-between items-center sm:mx-auto sm:justify-between">
    <div class="px-2 w-[20%] sm:px-4">
        <h2 class="font-bold">Participants</h2>
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
    <div class="mx-4 pb-2 text-justify">
        <h2>{{ $table->description }}</h2>
    </div>
@endif
