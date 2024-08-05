<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'explanation',
        'can_create_table',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
    ];

    /**
     * Get the french full day name
     */
    protected function fullDayName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst(Carbon::create(Carbon::getDays()[$this->date->format('w')])->locale('fr_FR')->dayName),
        );
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
