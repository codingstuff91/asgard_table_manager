<?php

namespace App\Repositories;

use App\Models\Game;

class GameRepository
{
    public function findOrFail(int $id): Game
    {
        return Game::findOrFail($id);
    }
}
