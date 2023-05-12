<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Game;
use App\Models\User;
use App\Models\Table;
use App\Models\Category;
use App\Events\TableCreated;
use Illuminate\Http\Request;
use App\Events\UserTableSubscribed;
use Illuminate\Support\Facades\Auth;
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
        $table = Table::create([
            'organizer_id'   => Auth::user()->id,
            'day_id'         => $day->id,
            'game_id'        => $request->game_id,
            'players_number' => $request->players_number,
            'total_points'   => $request->total_points,
            'start_hour'     => $request->start_hour,
            'description'    => $request->description,
        ]);

        event(new TableCreated($table, $request->user(), $day, (int)$request->game_id));

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

        return redirect()->back();
    }

    public function destroy(Table $table)
    {
        $table->users()->detach();

        $table->delete();

        return redirect()->back();
    }
}
