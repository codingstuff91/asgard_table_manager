<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Game;
use App\Models\User;
use App\Models\Table;
use App\Models\Category;
use App\Events\TableCreated;
use App\Events\TableDeleted;
use App\Events\TableUpdated;
use Illuminate\Http\Request;
use App\Events\UserTableSubscribed;
use Illuminate\Support\Facades\Auth;
use App\Events\UserTableUnsubscribed;
use App\Http\Requests\TableStoreRequest;
use App\Providers\SendDiscordTableCreatedNotification;

class TableController extends Controller
{
    public function create(Day $day)
    {
        $categories = Category::all();

        $current_url = session()->put('create_table_url', url()->current());

        return view('table.create', compact('day', 'categories'));
    }

    public function store(Day $day, TableStoreRequest $request)
    {
        $game = Game::findOrFail($request->game_id);

        $table = Table::create([
            'organizer_id'   => Auth::user()->id,
            'day_id'         => $day->id,
            'game_id'        => $game->id,
            'players_number' => $request->players_number,
            'total_points'   => $request->total_points,
            'start_hour'     => $request->start_hour,
            'description'    => $request->description,
        ]);

        event(new TableCreated($table, $day, $game));

        return redirect()->route('days.show', $day);
    }

    public function edit(Table $table)
    {
        $categories = Category::all();
        $games = Game::all();

        $day = $table->day;
        $tableGame = $table->game;
        $tableGameCategory = $table->game->category;

        return view('table.edit', compact('table', 'day', 'categories', 'games', 'tableGame', 'tableGameCategory'));
    }

    public function update(Table $table, Request $request)
    {
        $day = $table->day;

        $table->update([
            'players_number' => $request->players_number,
            'total_points'   => $request->total_points,
            'start_hour'     => substr($request->start_hour, 0, 5),
            'description'    => $request->description,
        ]);

        event(new TableUpdated($table, $request->user(), $day, (int)$request->game_id));

        return redirect()->route('days.show', $day);
    }

    public function subscribe(Table $table, User $user)
    {
        $table->users()->attach($user);

        event(new UserTableSubscribed($user, $table));

        return redirect()->back();
    }

    public function unSubscribe(Table $table, User $user)
    {
        $table->users()->detach($user);

        event(new UserTableUnsubscribed($user, $table));

        return redirect()->back();
    }

    public function destroy(Table $table)
    {
        $game = $table->game;
        $day = $table->day;
        $user = $table->organizer;

        $table->users()->detach();

        $table->delete();

        event(new TableDeleted($table, $day, $game, $user));

        return redirect()->back();
    }
}
