<?php

namespace App\Logic;

use App\DataObjects\TableData;
use App\Models\Day;
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

    public static function canCreateTable(Day $day): bool
    {
        return $day->can_create_table;
    }

    public static function saveThreadId(int $threadId, Table $table): void
    {
        $table->discord_thread_id = $threadId;

        $table->save();
    }

    public static function saveMessageId(int $messageId, Table $table): void
    {
        $table->discord_message_id = $messageId;

        $table->save();
    }
}
