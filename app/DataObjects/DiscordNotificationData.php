<?php

namespace App\DataObjects;

use App\Models\Day;
use App\Models\Game;
use App\Models\Table;

class DiscordNotificationData
{
    public function __construct(
        public ?Game $game,
        public ?Table $table,
        public ?Day $day,
        public array $extra = [],
    ) {
    }

    /**
     * @param  array<mixed>  $extra
     */
    public static function make(
        ?Game $game,
        ?Table $table,
        ?Day $day,
        array $extra = [],
    ): DiscordNotificationData {
        return new self(
            game: $game,
            table: $table,
            day: $day,
            extra: $extra,
        );
    }

    public function gameName(): string
    {
        return $this->game->name;
    }

    public function getCategory()
    {
        return $this->game->category->name;
    }

    public function getDay()
    {
        return $this->day->date->format('d/m/Y');
    }

    public function getStartHour(): string
    {
        return $this->table->start_hour;
    }

    public function getDescription(): ?string
    {
        return $this->table->description;
    }
}
