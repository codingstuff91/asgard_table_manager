<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'players_number',
        'category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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
