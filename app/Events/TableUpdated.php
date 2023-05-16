<?php

namespace App\Events;

use App\Models\Day;
use App\Models\User;
use App\Models\Table;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
