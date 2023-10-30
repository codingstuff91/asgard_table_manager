<div class="w-full bg-gray-200 rounded-lg">
    <div id="title" class="bg-green-500 p-2 text-white flex justify-between rounded-t-lg">
        <h2 class="pl-2 text-lg font-bold">Titre du jeu</h2>
        <h2 class="pr-2 text-lg font-bold">Heure début</h2>
    </div>
    <div id="main-body" class="mt-2 p-2 flex w-full justify-between border-2 sm:w-2/3 sm:mx-auto sm:justify-between">
        <div class="ml-4">
            <h2>Organisateur</h2>
            <h3>{{ $table->organizer->name }}</h3>
        </div>
        <div class="mx-4">
            <h2>Joueurs</h2>
            <h3
                class="bg-blue-300 text-sm p-1 border-2 border-gray-500 rounded-lg text-center"
                wire:click="togglePlayers">
                {{ $table->users->count() }} / {{ $table->players_number }}
            </h3>
        </div>
        @if($table->description)
        <div class="mx-4">
            <h2>Description</h2>
            <button
                class="bg-blue-300 p-1 rounded-lg border-2 border-gray-500 text-sm"
                wire:click="toggleDescription">
                Détails
            </button>
        </div>
        @endif
    </div>

    @if($showDescription)
        <div class="w-full p-2 text-center">
            <p>{{ $table->description }}</p>
        </div>
    @endif

    @if($showPlayers)
        <div class="w-full p-2 text-center">
            @foreach($table->users as $user)
                <p>{{ $user->name }}</p>
            @endforeach
        </div>
    @endif

    <div id="action_buttons" class="mt-2 pb-2 flex justify-center">
        <img src="{{ asset('img/add-user.png')}}"  class="h-6 w-6 mx-4">
        <img src="{{ asset('img/edit.png')}}" class="h-6 w-6 mx-4">
        <img src="{{ asset('img/delete.png')}}" class="h-6 w-6 mx-4">
    </div>
</div>

