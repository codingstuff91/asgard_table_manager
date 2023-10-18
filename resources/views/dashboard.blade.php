<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-2 rounded-lg mt-4 mx-auto lg:grid-cols-5 lg:gap-2">
                <div class="p-6 bg-gray-500 rounded-lg">
                    <h2 class="text-xl text-center text-gray-900 dark:text-white">Utilisateurs inscrits</h2>
                    <p class="mt-2 text-center text-lg dark:text-white">{{ $users }}</p>
                </div>
                <div class="p-6 bg-gray-500 rounded-lg">
                    <h2 class="text-xl text-center text-gray-900 dark:text-white">Sessions créées</h2>
                    <p class="mt-2 text-center text-lg dark:text-white">{{ $days }}</p>
                </div>
                <div class="p-6 bg-gray-500 rounded-lg">
                    <h2 class="text-xl text-center text-gray-900 dark:text-white">Tables créées</h2>
                    <p class="mt-2 text-center text-lg dark:text-white">{{ $tables }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
