<?php

namespace App\Actions\Day;

use App\Models\Day;

class DeleteDayTablesAction
{
    public function execute(Day $day): void
    {
        $day->tables()->delete();
    }
}
