<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Table;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = User::all()->count();

        $days = Day::all()->count();

        $tables = Table::all()->count();

        return view('dashboard', compact('users', 'days', 'tables'));
    }
}
