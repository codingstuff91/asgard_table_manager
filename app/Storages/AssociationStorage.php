<?php

namespace App\Storages;

use App\Models\Association;

class AssociationStorage
{
    use BaseStorage;

    public const STORAGE_KEY = 'association';

    public static function current(): Association
    {
        return session()->get(self::getStorageKey());
    }
}
