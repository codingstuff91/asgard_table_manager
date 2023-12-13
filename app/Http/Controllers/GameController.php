<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    /**
     * Redirect to create game admin panel page
     */
    public function create()
    {
        return redirect('/admin/games/create');
    }

    public function searchByCategory(Request $request)
    {
        return Game::where('category_id', $request->category)->get();
    }
}
