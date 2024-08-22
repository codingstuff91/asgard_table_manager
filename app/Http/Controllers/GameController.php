<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Redirect to create game admin panel page
     */
    public function create(): RedirectResponse
    {
        return redirect('/admin/games/create');
    }

    public function searchByCategory(Request $request): Collection
    {
        return Game::where('category_id', $request->category)
            ->orderBy('name', 'asc')
            ->get();
    }
}
