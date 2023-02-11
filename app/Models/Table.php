<?php

namespace App\Models;

use App\Models\Day;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class);    
    }
}
