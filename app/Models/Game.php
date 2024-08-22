<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'players_number',
        'category_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'players_number' => 'integer',
        'category_id' => 'integer',
    ];

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
