<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ajout d'une session
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                <form action="{{ route('days.store') }}" method="POST" class="flex flex-col justify-center items-center">
                    @csrf

                    <x-input-label>Date de la session</x-input-label>
                    <x-text-input 
                        type="date"
                        class="w-full" 
                        name="date" 
                        value="{{ old('date') }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('date')" />
                    
                    <x-primary-button class="mt-4">
                        Confirmer
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>