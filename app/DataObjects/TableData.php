<?php

namespace App\DataObjects;

use App\Models\Day;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\TableStoreRequest;

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

    public static function make(Day $day, TableStoreRequest $request)
    {
        return new self(
            $day->id,
            $request->user()->id,
            $request->game_id,
            $request->players_number,
            $request->total_points,
            $request->start_hour,
            $request->description ?? null,
        );
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
