<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }
}
