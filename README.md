## A propos

Cette application web a été crée pour faciliter la gestion des tables de jeux pour l'association ASGARD FIGURINES.

## Fonctionnalités

Cette application permet aux utilisateurs de réaliser les actions suivantes :

- Afficher la liste des sessions de jeux proposées par l'association.
- Création, mise à jour et suppression de table de jeu avec envoi de notification à un serveur discord.
- Inscription et désinscription à une table de jeu avec envoi de notification à un serveur discord
- Gestion de la liste de jeux proposée par l'association

## Technologies

Cette application a été crée grace aux technologies suivantes:

- Laravel 9
- PestPHP
- Vite

Les outils d'analyse suivants ont été utilisés :

- Laravel PINT : verification et correction des conventions de codage

## Lancer le projet en local

Pour utiliser le projet en local il faut réaliser les taches suivantes :

- Créer un fichier .env dans votre projet en prenant comme exemple le fichier .env.example
- Executer la commande artisan : ``php artisan migrate:fresh --seed``
- Exécuter la commande : `npm run dev`
- Exécuter la commande : `php artisan serve`
