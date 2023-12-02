<div class="my-4">
    <x-input-label class="text-center">Choix du jeu</x-input-label>
    <x-text-input
        type="text"
        wire:model.live="searchGameInput"
        class="w-full mt-4 border-2 border-red-500"
        name="game_search"
        placeholder="Nom du jeu. Par ex : Aliens"
        value="{{ old('game_search') }}">
    </x-text-input>

    <select class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="game_id">
        <option value="">{{ $gamesCount == 0 ? "Aucun jeu trouvé" : $gamesCount . " Jeux trouvés" }}</option>
        @foreach ($games as $game)
            <option value="{{ $game->id }}">{{ $game->name }}</option>
        @endforeach
    </select>
</div>
