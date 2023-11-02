<?php

namespace App\Events;

use App\Models\Day;
use App\Models\Table;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TableUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Table $table, User $user, Day $day, int $game)
    {
        $this->table = $table;
        $this->user = $user;
        $this->day = $day;
        $this->game = $game;
    }
}
