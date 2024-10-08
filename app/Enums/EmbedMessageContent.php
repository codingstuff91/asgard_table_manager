<?php

namespace App\Enums;

enum EmbedMessageContent: string
{
    case CREATED = 'Nouvelle table proposée';
    case UPDATED = 'Mise à jour de table';
    case DELETED = 'Annulation de table';
    case SUBSCRIBED = 'Inscription de joueur';
    case UNSUBSCRIBED = 'Désinscription de joueur';
    case CANCELLED = 'Annulation de session';
    case WARNING = 'Avertissement de session';
    case EVENT_CREATED = 'Nouvel évènement ajouté';
    case EVENT_UPDATED = 'Evènement mis à jour';
    case EVENT_CANCELLED = 'Evènement annulé';
}
