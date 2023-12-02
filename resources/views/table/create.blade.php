<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Création d'une table pour la session du {{ $day->date->format('d/m/Y') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                <form action="{{ route('table.store', $day->id) }}" method="POST" class="flex flex-col justify-center items-center">
                    @csrf

                    <livewire:game-selection-modal />
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
                        value="{{ old('players_number') }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('players_number')" />

                    <x-input-label class="mt-2">Nombre points</x-input-label>
                    <x-text-input
                        type="number"
                        class="w-full"
                        name="total_points"
                        placeholder="Nombre de points"
                        value="{{ old('total_points') }}">
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

                    <x-input-label class="mt-2">Description complémentaire</x-input-label>
                    <textarea
                        name="description"
                        id="description"
                        class="w-full rounded-lg border border-gray-300"
                        placeholder="Ajoutez ici des informations complémentaires concernant votre table"
                        rows="3">
                        {{ old('description') }}
                    </textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />

                    <x-primary-button class="mt-4">
                        Créer la table
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var category = document.getElementById("gameCategory");
        var description = document.getElementById("description");

        description.innerText = "";

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
