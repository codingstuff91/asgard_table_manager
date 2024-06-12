<?php

namespace App\Actions\Day;

use App\Models\Day;

class DisableDayAction
{
    public function execute(Day $day, string $explanation): void
    {
        $day->update([
            'explanation' => $explanation,
            'can_create_table' => false,
        ]);
    }
}
