<div id="main-body" class="mt-1 px-2 pb-2 w-full sm:mx-auto">
    <div class="px-2">
        <h2 class="font-bold">Participants</h2>
        <div class="flex justify-between">
            <div class="w-3/4">
                @foreach($event->users as $user)
                    <span>{{ $user->name }}, </span>
                @endforeach
            </div>
            <div>
                @if (collect($event->users)->pluck('name')->doesntContain(Auth::user()->name))
                    <button>
                        <a
                            href="{{ route('event.subscribe', [$event->id, Auth::user()->id]) }}"
                            onclick="return confirm('Etes-vous certain de vouloir y participer ?')"
                        >
                            <img src="{{ asset('img/add-user.png')}}" class="w-8 h-8">
                        </a>
                    </button>
                @endif

                @if (collect($event->users)->pluck('name')->contains(Auth::user()->name))
                    <button>
                        <a
                            href="{{ route('event.unsubscribe', [$event->id, Auth::user()->id]) }}"
                            onclick="return confirm('Etes-vous certain de ne plus participer ?')"
                        >
                            <img src="{{ asset('img/delete-user.png') }}" class="w-8 h-8">
                        </a>
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-2 px-2 pb-2 text-justify">
        <h2 class="font-bold">Description</h2>
        <p class="text-justify">{{ $event->description }}</p>
    </div>
</div>

