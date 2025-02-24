<?php

namespace App\Repositories;

use App\Storages\AssociationStorage;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function allForCurrentAssociation(): ?Collection
    {
        $association = AssociationStorage::current();

        return $association->categories;
    }
}
