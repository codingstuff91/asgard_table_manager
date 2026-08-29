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

    /**
     * @phpstan-ignore-next-line
     */
    public function days(): HasMany
    {
        return $this->hasMany(Day::class);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
