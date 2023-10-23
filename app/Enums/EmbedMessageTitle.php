<?php

namespace App\Enums;

enum EmbedMessageTitle: string
{
    case CREATED = 'Nouvelle table disponible sur ASGARD-TABLE-MANAGER';
    case UPDATED = 'Mise à jour de table';
    case DELETED = 'Annulation de table';
}
