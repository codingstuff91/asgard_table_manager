<?php

namespace App\Repositories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

class GameRepository
{
    public function findOrFail(int $id): Game
    {
        return Game::findOrFail($id);
    }

    // @phpstan-ignore-next-line
    public function findByCategory(string $categoryId): Collection
    {
        return Game::where('category_id', $categoryId)
            ->orderBy('name', 'asc')
            ->get();
    }
}
