<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mise à jour de table du {{ $day->date->format('d/m/Y') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                <form action="{{ route('table.store', $day->id) }}" method="POST" class="flex flex-col justify-center items-center">
                    @csrf
                    
                    <x-input-label>Catégorie de jeu</x-input-label>
                    <select class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        name="category_id"
                        id="gameCategory">
                        <option value="">x-- Choisir une catégorie --x</option>
                        @foreach ($categories as $category)
                            <option 
                                value="{{ $category->id }}"
                                {{ $category->id === $tableGameCategory->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('category_id')" />

                    <x-input-label class="mt-2">Nom du jeu</x-input-label>
                    <select class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        name="game_id"
                        id="gameName">
                        <option value="">x-- Choisir un jeu --x</option>
                        @foreach ($games as $game)
                            <option 
                                value="{{ $game->id }}"
                                {{ $game->id == $tableGame->id ? 'selected' : '' }}>
                                    {{ $game->name }}
                            </option> 
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('game_id')" />
                    
                    <x-secondary-button class="my-2">
                        <a href="{{ route('games.create') }}">Ajouter un jeu</a>
                    </x-secondary-button>

                    <x-input-label class="mt-2">Nombre joueurs max</x-input-label>
                    <x-text-input 
                        type="number" 
                        class="w-full" 
                        name="players_number" 
                        placeholder="Nombre de joueurs" 
                        value="{{ $table->players_number }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('players_number')" />

                    <x-input-label class="mt-2">Nombre points</x-input-label>
                    <x-text-input 
                        type="number" 
                        class="w-full" 
                        name="total_points" 
                        placeholder="Nombre de points" 
                        value="{{ $table->total_points }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('total_points')" />


                    <x-input-label class="mt-2">Heure de début</x-input-label>
                        <x-text-input 
                        type="time" 
                        class="w-full" 
                        name="start_hour"
                        value="{{ old('start_hour') }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('start_hour')" />
                    
                    <x-primary-button class="mt-4">
                        Créer la table
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
    <script>        
        // récupérer l'élément <select> avec l'ID "monSelect"
        var category = document.getElementById("gameCategory");

        // ajouter un écouteur d'événement "change" à l'élément <select>
        category.addEventListener("change", function() {
            var gameList = document.getElementById("gameName");
            gameList.innerHTML = '';

            const defaultOption = document.createElement("option");
            defaultOption.text = "x-- Choisir un jeu --x";
            defaultOption.value = '';

            gameList.appendChild(defaultOption);

            axios.get('/games/search', {
                params : {
                    category: category.value,
                }
            }).then((response) => {
                console.log(response.data);
                response.data.forEach(game => {
                    const option = document.createElement("option");
                    option.text = game.name;
                    option.value = game.id;

                    gameList.appendChild(option);
                });
            });
        });

    </script>
</x-app-layout>