<?php

namespace App\Http\Controllers;

use App\Actions\Discord\CreateDiscordNotificationAction;
use App\Actions\UserSubscriptionAction;
use App\DataObjects\DiscordNotificationData;
use App\DataObjects\TableData;
use App\Enums\GameCategory;
use App\Http\Requests\TableStoreRequest;
use App\Logic\TableLogic;
use App\Logic\UserLogic;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TableController extends Controller
{
    public function __construct(
        public CreateDiscordNotificationAction $createDiscordNotificationAction,
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

        $tableAttributes = TableData::fromRequest($day, $request);

        if (TableLogic::isAlreadyExists($tableAttributes)) {
            return to_route('days.show', $day)->with(['error' => 'Vous ne pouvez pas créer 2 fois la même table']);
        }

        $table = Table::create($tableAttributes->toArray());
        $user = $request->user();

        if ($game->category->id !== GameCategory::ROLE_PLAYING_GAME->value) {
            app(UserSubscriptionAction::class)->execute($table, $user);
        }

        $discordNotificationData = $this->discordNotificationData::make($game, $table, $day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'create');

        return redirect()->route('days.show', $day);
    }

    public function edit(Table $table)
    {
        if (! Gate::allows('edit_table', $table)) {
            abort(403);
        }

        $categories = Category::all();
        $games = Game::all();

        $day = $table->day;
        $tableGame = $table->game;
        $tableGameCategory = $table->game->category;

        return view('table.edit', compact('table', 'day', 'categories', 'games', 'tableGame', 'tableGameCategory'));
    }

    public function update(Table $table, TableStoreRequest $request): RedirectResponse
    {
        $tableAttributes = TableData::fromRequest($table->day, $request);

        $table->update($tableAttributes->toArray());

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'update');

        return redirect()->route('days.show', $table->day);
    }

    public function subscribe(Table $table): RedirectResponse
    {
        if ($table->users()->count() === $table->players_number) {
            return redirect()->route('days.show', $table->day)->with(['error' => 'Nombre maximum de joueurs atteint']);
        }

        if (app(UserLogic::class)->hasSubscribedToAnotherTableWithTheSameStartHour($table->day, $table)) {
            return redirect()->route('days.show', $table->day)->with(['error' => 'Vous êtes déjà inscrit à une autre table à la même heure ce jour là']);
        }

        $table->users()->attach(Auth::user());

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'subscribe');

        return redirect()->back();
    }

    public function unSubscribe(Table $table): RedirectResponse
    {
        $table->users()->detach(Auth::user());

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'unsubscribe');

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
