<?php

namespace App\Handlers;

use App\Actions\Discord\CreateDiscordNotificationAction;
use App\Actions\UserSubscriptionAction;
use App\Commands\CreateTableCommand;
use App\Commands\UpdateTableCommand;
use App\DataObjects\DiscordNotificationData;
use App\DataObjects\TableData;
use App\Enums\GameCategory;
use App\Logic\TableLogic;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use App\Repositories\GameRepository;
use App\Services\DiscordNotificationService;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateTableHandler
{
    public function __construct(
        protected UserSubscriptionAction $userSubscriptionAction,
        protected CreateDiscordNotificationAction $createDiscordNotificationAction,
        protected GameRepository $gameRepository,
        protected DiscordNotificationData $discordNotificationData,
        protected DiscordNotificationService $discordNotificationService,
    ) {
        //
    }

    public function handleUpdate(UpdateTableCommand $command)
    {
        try {
            $tableAttributes = TableData::fromRequest($command->table->day, $command->request);

            $command->table->update($tableAttributes->toArray());

            $this->sendDiscordNotification($command->table->game, $command->table, $command->table->day);
        } catch (Exception $e) {
            Log::error('Error updating table: '.$e->getMessage());

            return redirect()->route('days.show', $command->table->day)->with(['error' => 'Une erreur est survenue lors de la mise à jour de la table.']);
        }
    }

    private function sendDiscordNotification(Game $game, Table $table, Day $day): void
    {
        $this->discordNotificationService->sendDiscordNotification($game, $table, $day);
    }
}
