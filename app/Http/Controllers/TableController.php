<?php

namespace App\Http\Controllers;

use App\Actions\Discord\CreateDiscordNotificationAction;
use App\Actions\UserSubscriptionAction;
use App\DataObjects\DiscordNotificationData;
use App\DataObjects\TableData;
use App\Events\UserTableSubscribed;
use App\Events\UserTableUnsubscribed;
use App\Http\Requests\TableStoreRequest;
use App\Logic\TableLogic;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct(
        public CreateDiscordNotificationAction $createDiscordNotificationAction,
        public CreateUserDiscordNotificationAction $createUserDiscordNotificationAction,
        public DiscordNotificationData $discordNotificationData,
    ) {
    }

    public function create(Day $day)
    {
        $categories = Category::all();

        $current_url = session()->put('create_table_url', url()->current());

        return view('table.create', compact('day', 'categories'));
    }

    public function store(Day $day, TableStoreRequest $request)
    {
        $game = Game::findOrFail($request->game_id);

        $tableAttributes = TableData::make($day, $request);

        if (TableLogic::isAlreadyExists($tableAttributes)) {
            return to_route('days.show', $day)->with(['error' => 'Vous ne pouvez pas créer 2 fois la même table']);
        }

        $table = Table::create($tableAttributes->toArray());
        $user = $request->user();

        app(UserSubscriptionAction::class)->execute($table, $user);

        $discordNotificationData = $this->discordNotificationData::make($game, $table, $day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'create');

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

    public function update(Table $table, Request $request): RedirectResponse
    {
        $table->update([
            'players_number' => $request->players_number,
            'total_points' => $request->total_points,
            'start_hour' => substr($request->start_hour, 0, 5),
            'description' => $request->description,
        ]);

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'update');

        return redirect()->route('days.show', $table->day);
    }

    public function subscribe(Table $table, User $user): RedirectResponse
    {
        $playersNumber = $table->users()->count();

        if ($playersNumber === $table->players_number) {
            return redirect()->route('days.show', $table->day)->with(['error' => 'Nombre maximum de joueurs atteint']);
        }

        $table->users()->attach($user);

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        ($this->createUserDiscordNotificationAction)($discordNotificationData, 'subscribe');

        return redirect()->back();
    }

    public function unSubscribe(Table $table, User $user): RedirectResponse
    {
        $table->users()->detach($user);

        ($this->createUserDiscordNotificationAction)($discordNotificationData, 'unsubscribe');

        return redirect()->back();
    }

    public function destroy(Table $table): RedirectResponse
    {
        $table->users()->detach();

        $table->delete();

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'delete');

        return redirect()->back();
    }
}
