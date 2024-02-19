<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mise à jour d'un évènement
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                <form action="{{ route('event.update', $event) }}" method="POST" class="flex flex-col justify-center items-center">
                    @csrf
                    @method('PUT')

                    <x-input-label>Nom de l'évènement</x-input-label>
                    <x-text-input
                        type="text"
                        class="w-full"
                        name="name"
                        value="{{ $event->name }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />

                    <x-input-label class="mt-2">Heure de début</x-input-label>
                    <x-text-input
                        type="time"
                        class="w-full"
                        name="start_hour"
                        value="{{ $event->start_hour }}">
                    </x-text-input>
                    <x-input-error class="mt-2" :messages="$errors->get('start_hour')" />

                    <x-input-label class="mt-2">Description complémentaire</x-input-label>
                    <textarea
                        name="description"
                        id="description"
                        class="w-full rounded-lg border border-gray-300"
                        placeholder="Ajoutez ici des informations complémentaires"
                    >
                        {{ $event->description }}
                    </textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />

                    <x-primary-button class="mt-4">
                        Confirmer
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
