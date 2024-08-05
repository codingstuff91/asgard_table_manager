<?php

namespace App\Enums;

enum EmbedMessageContent: string
{
    case CREATED = 'Nouvelle table disponible sur ASGARD-TABLE-MANAGER';
    case UPDATED = 'Mise à jour de table';
    case DELETED = 'Annulation de table';
    case SUBSCRIBED = 'Inscription de joueur';
    case UNSUBSCRIBED = 'Désinscription de joueur';
    case CANCELLED = 'Annulation de session';
    case WARNING = 'Avertissement de session';
}
