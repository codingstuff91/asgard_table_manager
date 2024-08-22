<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }
}
