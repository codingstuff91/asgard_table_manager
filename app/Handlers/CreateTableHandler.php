<?php

namespace App\Handlers;

use App\Actions\Discord\CreateDiscordNotificationAction;
use App\Actions\UserSubscriptionAction;
use App\Commands\CreateTableCommand;
use App\DataObjects\TableData;
use App\Enums\GameCategory;
use App\Logic\TableLogic;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use App\Repositories\GameRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateTableHandler
{
    public function __construct(
        protected UserSubscriptionAction $userSubscriptionAction,
        protected CreateDiscordNotificationAction $createDiscordNotificationAction,
        protected GameRepository $gameRepository,
    )
    {
        //
    }

    public function handle(CreateTableCommand $command)
    {
        $day = $command->day;
        $request = $command->request;

        try {
            $game = $this->gameRepository->findOrFail($request->game_id);
            $tableAttributes = TableData::fromRequest($day, $request);

            $this->checkIfTableExists($tableAttributes);

            $table = $this->createTable($tableAttributes);

            $this->handleUserSubscription($game, $table, $request->user());
            $this->sendDiscordNotification($game, $table, $day);

            return redirect()->route('days.show', $day);
        } catch (Exception $e) {
            Log::error('Error creating table: ' . $e->getMessage());

            return redirect()->route('days.show', $day)->with(['error' => 'Une erreur est survenue lors de la création de la table.']);
        }
    }

    private function checkIfTableExists(TableData $tableAttributes)
    {
        if (TableLogic::isAlreadyExists($tableAttributes)) {
            throw new Exception('Vous ne pouvez pas créer 2 fois la même table');
        }
    }

    private function createTable(TableData $tableAttributes): Table
    {
        return Table::create($tableAttributes->toArray());
    }

    private function handleUserSubscription(Game $game, Table $table, User $user)
    {
        if ($game->category->id !== GameCategory::ROLE_PLAYING_GAME->value) {
            $this->userSubscriptionAction->execute($table, $user);
        }
    }

    private function sendDiscordNotification(Game $game, Table $table, Day $day)
    {
        dd('envoi notif discord');
        if (App::environment('production')) {
            $discordNotificationData = $this->discordNotificationData::make($game, $table, $day);
            ($this->createDiscordNotificationAction)($discordNotificationData, 'create');
//            $discordNotificationData = $this->sendDiscordNotificationAction::make($game, $table, $day);
//            $this->sendDiscordNotificationAction->create($discordNotificationData, 'create');
        }
    }
}
