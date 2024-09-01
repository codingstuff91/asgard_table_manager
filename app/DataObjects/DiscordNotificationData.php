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
}
