<?php

namespace App\Http\Controllers;

use App\Actions\UserSubscriptionAction;
use App\DataObjects\TableData;
use App\Logic\TableLogic;
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
use App\Events\UserTableUnsubscribed;
use App\Http\Requests\TableStoreRequest;

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
        $tableAttributes = TableData::make($day, $request);

        if (TableLogic::isAlreadyExists($tableAttributes)) {
            return to_route('days.show', $day)->with(['error' => 'Vous ne pouvez pas créer 2 fois la même table']);
        }

        $table = Table::create($tableAttributes->toArray());
        $user = $request->user();

        app(UserSubscriptionAction::class)->execute($table, $user);

        event(new TableCreated($table, $request->user(), $day, $request->game_id));

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
        $numerOfPlayers = $table->users()->count();

        if ($numerOfPlayers === $table->players_number) {
            return redirect()->route('days.show', $table->day)->with(['error' => 'Nombre maximum de joueurs atteint']);
        }

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
