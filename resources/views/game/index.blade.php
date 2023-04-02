<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Liste des jeux
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full mx-auto sm:px-6 lg:w-2/3 lg:px-8">
            <div class="my-4 flex justify-center">
                <x-primary-button>
                    <a href="{{ route('games.create') }}">Ajouter un jeu</a>
                </x-primary-button>
            </div>
            <div class="bg-white w-full">
                <div class="flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full">
                            <div class="overflow-hidden">
                                <table class="min-w-full">
                                    <thead class="bg-gray-400 border-b">
                                        <tr>
                                            <th scope="col" class="text-sm font-medium text-white px-6 py-4 text-left">
                                                #
                                            </th>
                                            <th scope="col" class="text-sm font-medium text-white px-6 py-4 text-left">
                                                Catégorie
                                            </th>
                                            <th scope="col" class="text-sm font-medium text-white px-6 py-4 text-left">
                                                Nom
                                            </th>
                                            <th scope="col" class="text-sm font-medium text-white px-6 py-4 text-left">
                                                
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($games as $game)
                                            <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $game->id }}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-2 py-4 whitespace-nowrap">
                                                    {{ $game->category->name }}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-2 py-4 whitespace-nowrap">
                                                    {{ $game->name }}
                                                </td>
                                                <td class="text-sm text-gray-900 font-light px-2 py-4 whitespace-nowrap flex justiy-center">
                                                    <button class="mx-2">
                                                        <a href="{{ route('games.edit', $game->id) }}">
                                                            <i class="fa-solid fa-file-pen fa-2x"></i>
                                                        </a>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>