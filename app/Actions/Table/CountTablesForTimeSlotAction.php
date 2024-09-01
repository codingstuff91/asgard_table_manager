<?php

namespace App\Actions\Table;

use Illuminate\Database\Eloquent\Collection;

class CountTablesForTimeSlotAction
{
    public function __invoke(
        Collection $tables,
        int $startHour,
        int $endHour
    ): int {
        return $tables->filter(function ($value) use ($startHour, $endHour) {
            $hour = explode(':', $value->start_hour)[0];

            return $hour >= $startHour && $hour < $endHour;
        })->count();
    }
}
