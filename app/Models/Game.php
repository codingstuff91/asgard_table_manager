<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
