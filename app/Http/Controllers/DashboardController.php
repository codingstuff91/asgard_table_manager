<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Table;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = User::count();

        $days = Day::count();

        $tables = Table::count();

        $afternoonTables = Table::all()->filter(function ($value, $key) {
            $hour = explode(':', $value->start_hour)[0];

            return $hour >= 13 && $hour <= 19;
        })->count();

        $eveningTables = Table::all()->filter(function ($value, $key) {
            $hour = explode(':', $value->start_hour)[0];

            return $hour > 19 && $hour < 23;
        })->count();

        return view('dashboard', compact('users', 'days', 'tables', 'afternoonTables', 'eveningTables'));
    }
}
