<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modification d'une session
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="sm:max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-full rounded-lg p-4">
                <form
                    action="{{ route('days.confirm_cancel', $day) }}"
                    method="POST"
                    class="flex flex-col justify-center items-center"
                >
                    @csrf
                    @method('PATCH')

                    <x-input-label>Message explicatif pour l'annulation de cette session</x-input-label>
                    <textarea
                        id="explanation"
                        name="explanation"
                        class="w-full rounded-lg border border-gray-300"
                        placeholder="Ajoutez ici votre message"
                        rows="3">
                        {{ old('explanation') }}
                    </textarea>

                    <x-input-error class="mt-2" :messages="$errors->get('explanation')" />

                    <x-primary-button class="mt-4">
                        Confirmer
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
    <script>
        let explanation = document.getElementById("explanation");

        explanation.innerText = "";
    </script>
</x-app-layout>
