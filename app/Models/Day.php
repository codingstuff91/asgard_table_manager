<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'explanation',
        'can_create_table',
    ];

    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
    ];

    /**
     * Get the french full day name
     *
     * @phpstan-ignore-next-line
     */
    protected function fullDayName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst(Carbon::create(Carbon::getDays()[$this->date->format('w')])->locale('fr_FR')->dayName),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Table>
     */
    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Event>
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
