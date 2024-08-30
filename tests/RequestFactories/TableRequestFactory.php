<?php

namespace Tests\RequestFactories;

use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use Illuminate\Contracts\Auth\Authenticatable;

class TableRequestFactory
{
    private Authenticatable $organizer;

    private Day $day;

    private ?Game $game;

    private Category $category;

    private int $players_number;

    private string $start_hour;

    public static function new(): self
    {
        return new self;
    }

    public function create(array $extra = []): array
    {
        return $extra + [
            'organizer_id' => $this->organizer->id,
            'day_id' => $this->day->id,
            'game_id' => $this->game->id ?? null,
            'category_id' => $this->category->id,
            'players_number' => $this->players_number,
            'start_hour' => $this->start_hour,
        ];
    }

    public function withOrganizer(Authenticatable $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function withDay(Day $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function withGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function withCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function withPlayersNumber(int $playersNumber): self
    {
        $this->players_number = $playersNumber;

        return $this;
    }

    public function withStartHour(string $startHour): self
    {
        $this->start_hour = $startHour;

        return $this;
    }
}
