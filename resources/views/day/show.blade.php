<x-app-layout>
    <x-slot name="header" class="flex flex-row justify-between">
        <h2 class="font-bold text-xl text-gray-800 text-center dark:text-gray-200 leading-tight lg:text-2xl">
            Session du {{ $day->date->format('d/m/Y') }}
        </h2>
        <div class="mt-2 flex flex-col items-center sm:flex-row sm:justify-center">
            <x-secondary-button class="my-2 sm:my-0">
                <img src="{{ asset('/img/game-table.png') }}" class="w-8 h-8 mr-2" alt="">
                <a href="{{ route('table.create', $day->id) }}">Ouvrir une table</a>
            </x-secondary-button>
            <x-secondary-button class="ml-0 sm:ml-2">
                <img src="{{ asset('/img/calendar.png') }}" class="w-8 h-8 mr-2" alt="">

                <a href="{{ route('event.create', $day->id) }}">Créer un événement</a>
            </x-secondary-button>
        </div>
    </x-slot>

    <div class="w-full mt-2 mx-auto lg:max-w-7xl lg:px-4">
        @if(session('error'))
            <div class="text-center text-xl text-white font-semibold bg-red-500 w-full p-2 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

            <h2 class="my-4 text-center text-xl font-bold text-center text-black dark:text-white">Filtres par catégories</h2>

            <div class="mt-4 grid grid-cols-4 gap-2 sm:grid-cols-4">
            @foreach($tablesCountPerCategory as $table)
                <x-table-count-category :table="$table" :id="$table->id" :color="$table->color" :icon="$table->icon"/>
            @endforeach
        </div>

        @if($events->count() != 0)
            <h2 class="my-2 text-black text-xl my-2 text-center font-bold dark:text-white">Evenements</h2>
            @foreach ($events as $event)
                <x-event-card :event="$event"/>
            @endforeach
        @endif

        <h2 class="text-black text-center text-xl font-bold mt-4 dark:text-white">Tables disponibles</h2>
        <div class="w-full rounded-lg mx-auto w-full sm:grid sm:grid-cols-2 sm:gap-2 lg:grid-cols-3 lg:gap-2">
            @foreach ($tables as $table)
                <x-table-card :table="$table" :color="$table->game->category->color"/>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.category').forEach(function (category) {
                category.addEventListener('click', function () {
                    let categoryId = this.getAttribute('category-id');

                    document.querySelectorAll('.game-table').forEach(function (table) {
                        table.style.display = 'none';
                    });

                    document.querySelectorAll('.game-table[data-category="' + categoryId + '"]').forEach(function (table) {
                        table.style.display = 'block';
                    });
                });
            });
        });
    </script>
</x-app-layout>
