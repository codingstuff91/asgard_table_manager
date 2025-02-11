<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Association extends Model
{
    use HasFactory;

    /**
     * @return BelongsToMany<\App\Models\User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function days(): HasMany
    {
        return $this->hasMany(Day::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
