<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Création d'un nouvel évènement
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                <form action="{{ route('event.store', $day) }}" method="POST" class="flex flex-col justify-center items-center">
                    @csrf

                    <x-input-label>Nom de l'évènement</x-input-label>
                    <x-text-input
                        type="text"
                        class="w-full"
                        name="name"
                        value="{{ old('name') }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />

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
                        placeholder="Ajoutez ici des informations complémentaires"
                        rows="3">
                        {{ old('description') }}
                    </textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />

                    <x-primary-button class="mt-4">
                        Confirmer
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var description = document.getElementById("description");

        description.innerText = "";
    </script>
</x-app-layout>
