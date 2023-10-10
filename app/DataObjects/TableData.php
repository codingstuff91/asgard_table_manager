<?php

namespace App\DataObjects;

use App\Http\Requests\TableStoreRequest;
use Illuminate\Support\Facades\Auth;

class TableData
{
    public function __construct(
        public int $day_id,
        public int $organizer_id,
        public int $game_id,
        public int $players_number,
        public int $total_points,
        public string $start_hour,
        public ?string $description = null,
    ){
    }

    public function toArray(): array
    {
        return [
            'organizer_id'   => $this->organizer_id,
            'day_id'         => $this->day_id,
            'game_id'        => $this->game_id,
            'players_number' => $this->players_number,
            'total_points'   => $this->total_points,
            'start_hour'     => $this->start_hour,
            'description'    => $this->description ?? null,
        ];
    }
}
