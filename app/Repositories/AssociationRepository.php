<?php

namespace App\Repositories;

use App\Models\Association;

class AssociationRepository
{
    public function findBySlug(string $slug): ?Association
    {
        return Association::where('slug', $slug)->first();
    }
}
