<?php

namespace App\DataObjects;

use App\Http\Requests\TableStoreRequest;
use App\Models\Day;

class TableData
{
    public function __construct(
        public int $day_id,
        public int $organizer_id,
        public int $game_id,
        public int $category_id,
        public int $players_number,
        public string $start_hour,
        public ?string $description = null,
    ) {
    }

    public static function fromRequest(Day $day, TableStoreRequest $request): self
    {
        return new self(
            $day->id,
            $request->user()->id,
            $request->game_id,
            $request->category_id,
            $request->players_number,
            $request->start_hour,
            $request->description ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'organizer_id' => $this->organizer_id,
            'day_id' => $this->day_id,
            'game_id' => $this->game_id,
            'category_id' => $this->category_id,
            'players_number' => $this->players_number,
            'start_hour' => $this->start_hour,
            'description' => $this->description ?? null,
        ];
    }
}
