<?php

namespace App\Handlers;

use App\Actions\UserSubscriptionAction;
use App\Commands\CreateTableCommand;
use App\DataObjects\TableData;
use App\Enums\GameCategory;
use App\Logic\TableLogic;
use App\Models\Table;
use Exception;
use Illuminate\Http\RedirectResponse;

class CreateTableHandler
{
    public function __construct(
        protected UserSubscriptionAction $userSubscriptionAction,
    ) {
        //
    }

    public function handle(CreateTableCommand $command): Table|RedirectResponse
    {
        $tableAttributes = TableData::fromRequest($command->day, $command->request);

        $this->checkIfTableExists($tableAttributes);

        $table = $this->createTable($tableAttributes);

        $this->registerCurrentUserToTable($table);

        return $table;
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
