<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ajout d'un jeu
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                <form action="{{ route('games.store') }}" method="POST" class="flex flex-col justify-center items-center">
                    @csrf
                    <x-input-label>Nom du jeu</x-input-label>
                    <x-text-input 
                        type="text" 
                        class="w-full" 
                        name="name" 
                        placeholder="Nom du jeu" 
                        value="{{ old('players_number') }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    
                    <x-primary-button class="mt-4">
                        Confirmer
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>