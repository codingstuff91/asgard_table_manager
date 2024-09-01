<?php

namespace App\Handlers;

use App\Commands\UpdateTableCommand;
use App\DataObjects\TableData;
use App\Models\Table;

class UpdateTableHandler
{
    public function handle(UpdateTableCommand $command): Table
    {
        $tableAttributes = TableData::fromRequest($command->table->day, $command->request);

        $command->table->update($tableAttributes->toArray());

        return $command->table;
    }
}
