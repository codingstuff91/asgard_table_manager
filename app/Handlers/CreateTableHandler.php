<?php

namespace App\Handlers;

use App\Actions\UserSubscriptionAction;
use App\Commands\CreateTableCommand;
use App\DataObjects\TableData;
use App\Enums\GameCategory;
use App\Logic\TableLogic;
use App\Models\Table;
use App\Repositories\GameRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateTableHandler
{
    public function __construct(
        protected UserSubscriptionAction $userSubscriptionAction,
        protected GameRepository $gameRepository,
    ) {
        //
    }

    public function handleCreate(CreateTableCommand $command)
    {
        try {
            $tableAttributes = TableData::fromRequest($command->day, $command->request);

            $this->checkIfTableExists($tableAttributes);

            $table = $this->createTable($tableAttributes);

            $this->registerCurrentUserToTable($table);
        } catch (Exception $e) {
            Log::error('Error creating table: '.$e->getMessage());

            return redirect()
                ->route('days.show', $command->day)
                ->with([
                    'error' => 'Une erreur est survenue lors de la création de la table.',
                ]);
        }
    }

    private function checkIfTableExists(TableData $tableAttributes): void
    {
        if (TableLogic::isAlreadyExists($tableAttributes)) {
            throw new Exception('Vous ne pouvez pas créer 2 fois la même table');
        }
    }

    private function createTable(TableData $tableAttributes): Table
    {
        return Table::create($tableAttributes->toArray());
    }

    private function registerCurrentUserToTable(Table $table): void
    {
        if ($table->category->id !== GameCategory::ROLE_PLAYING_GAME->value) {
            $this->userSubscriptionAction->execute($table);
        }
    }
}
