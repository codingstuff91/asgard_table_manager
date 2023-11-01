<div class="w-full bg-gray-200 rounded-lg mt-2">
    <div id="title" class="bg-green-500 p-2 text-white flex justify-between items-center rounded-t-lg">
        <div class="pl-2 w-[75%]">
            <h2 class="text-lg font-bold">Chroniques oubliées contemporain</h2>
            <h3 class="text-sm">Créateur : {{ $table->organizer->name }}</h3>
        </div>
        <div>
            <div class="flex justify-center mb-2">
                <img src="{{ asset('img/edit.png')}}" class="h-6 w-6 mr-4">
                <img src="{{ asset('img/delete.png')}}" class="h-6 w-6 ml-4">
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
            <img src="{{ asset('img/add-user.png')}}"  class="h-8 w-8">
        </div>
    </div>
    @if($table->description)
        <div class="mx-4 pb-2">
            <h2 class="font-bold">Description</h2>
            <p>{{ $table->description }}</p>
        </div>
    @endif
</div>

