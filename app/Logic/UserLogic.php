<?php

namespace App\Logic;

use App\Models\Day;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class UserLogic
{
    public function hasSubscribedToAnotherTableWithTheSameStartHour(Day $day, Table $table): bool
    {
        $tables = Table::where('start_hour', $table->start_hour)
            ->where('day_id', $day->id)
            ->where('id', '!=', $table->id)
            ->get();

        if ($tables->isEmpty()) {
            return false;
        }

        foreach ($tables as $table) {
            if ($table->users->contains(Auth::user())) {
                return true;
            }
        }

        return false;
    }

    public function isAlreadySubscribedToATable(Table $table): bool
    {
        $user = Auth::user();

        if ($table->users->contains(Auth::user()->id)) {
            throw new Exception('user already subscribed to table');

            return true;
        }

        return false;
    }
}
