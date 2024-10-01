<?php

namespace App\Http\Controllers;

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
use App\Notifications\Discord\NotificationFactory;
use App\Repositories\GameRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TableController extends Controller
{
    public function __construct(
        public DiscordNotificationData $discordNotificationData,
        public NotificationFactory $notificationFactory,
        public CreateTableHandler $createTableHandler,
        public UpdateTableHandler $updateTableHandler,
        public GameRepository $gameRepository,
    ) {
        //
    }

    public function create(Day $day): View
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

        try {
            $table = $this->createTableHandler->handle($command);

            $discordNotificationData = $this->discordNotificationData::make($game, $table, $day);

            $discordNotification = ($this->notificationFactory)(entity: 'table', type: 'create-table',
                discordNotificationData: $discordNotificationData);

            $discordNotification->handle();
        } catch (Exception $e) {
            Log::error('Problem during table creation: '.$e->getMessage());

            return redirect()
                ->route('days.show', $command->day)
                ->with([
                    'error' => 'Une erreur est survenue lors de la création de la table.',
                ]);
        }

        return redirect()->route('days.show', $command->day);
    }

    public function edit(Table $table): View
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
        try {
            $command = new UpdateTableCommand($table, $request);

            $table = $this->updateTableHandler->handle($command);

            $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

            $discordNotification = ($this->notificationFactory)(entity: 'table', type: 'update-table',
                discordNotificationData: $discordNotificationData);
            $discordNotification->handle();

        } catch (Exception $e) {
            Log::error('Problem during table update: '.$e->getMessage());

            return redirect()
                ->route('days.show', $table->day)
                ->with([
                    'error' => 'Une erreur est survenue lors de la mise à jour de la table.',
                ]);
        }

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

        $discordNotification = ($this->notificationFactory)(entity: 'user', type: 'user-subscription',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return redirect()->back();
    }

    public function unSubscribe(Table $table): RedirectResponse
    {
        $table->users()->detach(Auth::user());

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        $discordNotification = ($this->notificationFactory)(entity: 'user', type: 'user-unsubscription',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return redirect()->back();
    }

    public function destroy(Table $table): RedirectResponse
    {
        try {

            $table->users()->detach();

            $table->delete();

            $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

            $discordNotification = ($this->notificationFactory)(entity: 'table', type: 'cancel-table',
                discordNotificationData: $discordNotificationData);
            $discordNotification->handle();

        } catch (Exception $e) {
            Log::error('Problem during table cancellation: '.$e->getMessage());

            return redirect()
                ->route('days.show', $table->day)
                ->with([
                    'error' => 'Une erreur est survenue lors de l\'annulation de la table.',
                ]);
        }

        return redirect()->back();
    }
}
