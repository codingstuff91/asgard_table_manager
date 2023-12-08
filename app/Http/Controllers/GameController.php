<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameStoreRequest;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('game.create', compact('categories'));
    }

    public function searchByCategory(Request $request)
    {
        return Game::where('category_id', $request->category)->get();
    }
}
