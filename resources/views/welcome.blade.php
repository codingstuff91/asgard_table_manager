<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex flex-col justify-center items-center pt-8">
                    <img src="{{ asset('/img/logo_asgard.png') }}" class="w-32 h-32">
                    <h1 class="mt-2 text-3xl font-bold dark:text-gray-400">Table manager</h1>
                </div>

                <div class="my-4 mx-6 flex justify-center sm:w-[300px] sm:mx-auto">
                    @auth
                        <x-primary-button>
                            <a href="{{ route('dashboard') }}">Tableau de bord</a>
                        </x-primary-button>
                        <x-primary-button>
                            <a href="http://atm-documentation.jeuf5892.odns.fr/" target="_blank">Documentation</a>
                        </x-primary-button>
                    @else
                        <x-primary-button class="mx-2">
                            <a href="{{ route('register') }}">Inscription</a>
                        </x-primary-button>
                        <x-primary-button class="mx-2">
                            <a href="{{ route('login') }}">Connexion</a>
                        </x-primary-button>
                        <x-primary-button>
                            <a href="http://atm-documentation.jeuf5892.odns.fr/" target="_blank">Documentation</a>
                        </x-primary-button>
                    @endauth
                </div>


                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1">
                        <div class="p-6">
                            <div class="mt-2 text-lg text-gray-600 dark:text-gray-400 text-justify">
                                <p class="mt-2">Cette application web a été spécialement conçue pour aider les membres de l'association ASGARD à organiser des soirées de jeux de cartes, de plateaux et de rôles en toute simplicité. .</p>
                                <p class="mt-2">Grâce à cette plateforme, les organisateurs peuvent facilement gérer la création de tables de jeux et l'inscription des participants.</p>
                                <p class="mt-2">La première étape pour utiliser notre application consiste à créer un compte en ligne. Cela ne prend que quelques minutes et vous permettra d'accéder à toutes les fonctionnalités de l'application.
                                Une fois votre compte créé, vous pouvez créer une nouvelle soirée de jeux en sélectionnant le type de jeux que vous souhaitez proposer.</p>
                                <p class="mt-2">Ensuite, vous pouvez créer des tables de jeux en définissant le nombre de joueurs, le type de jeu, le nombre de points et l'heure de début.
                                Les joueurs intéressés peuvent ensuite s'inscrire sur ces tables.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        Copyright - Matthieu MARTIN - Tous droits réservés - 2023.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
