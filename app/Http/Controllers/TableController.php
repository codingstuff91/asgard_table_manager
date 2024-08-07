<?php

namespace App\Http\Controllers;

use App\Actions\Discord\CreateDiscordNotificationAction;
use App\Actions\UserSubscriptionAction;
use App\Commands\CreateTableCommand;
use App\Commands\UpdateTableCommand;
use App\DataObjects\DiscordNotificationData;
use App\Handlers\CreateTableHandler;
use App\Handlers\UpdateTableHandler;
use App\Http\Requests\TableStoreRequest;
use App\Logic\TableLogic;
use App\Logic\UserLogic;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Repositories\GameRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TableController extends Controller
{
    public function __construct(
        public CreateDiscordNotificationAction $createDiscordNotificationAction,
        public DiscordNotificationData $discordNotificationData,
        public CreateTableHandler $createTableHandler,
        public UpdateTableHandler $updateTableHandler,
        public GameRepository $gameRepository,
        protected UserSubscriptionAction $userSubscriptionAction,
    ) {
        //
    }

    public function create(Day $day)
    {
        if (! TableLogic::canCreateTable($day)) {
            abort(403);
        }

        $categories = Category::all();

        return view('table.create', compact('day', 'categories'));
    }

    public function store(Day $day, TableStoreRequest $request): RedirectResponse
    {
        $game = $this->gameRepository->findOrFail($request->game_id);

        $command = new CreateTableCommand($day, $game, $request);

        $table = $this->createTableHandler->handle($command);

        $discordNotificationData = $this->discordNotificationData::make($game, $table, $day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'create');

        return redirect()->route('days.show', $command->day);
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
        $command = new UpdateTableCommand($table, $request);

        $table = $this->updateTableHandler->handle($command);

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        ($this->createDiscordNotificationAction)($discordNotificationData, 'update');

        return redirect()->route('days.show', $command->table->day);
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
