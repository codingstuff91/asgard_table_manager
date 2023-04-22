<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\GameStoreRequest;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with('category')->get();

        return view('game.index')->with(['games' => $games]);
    }

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameStoreRequest $request)
    {
        Game::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
        ]);

        
        $redirectUrl = session()->get('create_table_url') ?? route('games.index');
        
        return redirect($redirectUrl);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        $categories = Category::all();

        return view('game.edit', [
            'game' => $game, 
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $game->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return to_route('games.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchByCategory(Request $request)
    {
        return Game::where('category_id', $request->category)->get();
    }
}
