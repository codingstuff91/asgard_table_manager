<?php

namespace App\Logic;

use App\DataObjects\TableData;
use App\Models\Table;

class TableLogic
{
    public static function isAlreadyExists(TableData $tableAttributes): bool
    {
        $table = Table::query()
            ->where('game_id', $tableAttributes->game_id)
            ->where('day_id', $tableAttributes->day_id)
            ->where('start_hour', $tableAttributes->start_hour)
            ->get();

        return $table->count() != 0;
    }
}
