<?php

namespace App\Http\Controllers;

use App\Actions\Table\CountTablesForTimeSlotAction;
use App\Models\Day;
use App\Models\Table;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        public CountTablesForTimeSlotAction $countTablesForTimeSlotAction,
    ) {
        //
    }

    public function __invoke(): View
    {
        $users = User::count();
        $days = Day::count();
        $tables = Table::count();

        $afternoonTables = ($this->countTablesForTimeSlotAction)(Table::all(), 13, 19);

        $eveningTables = ($this->countTablesForTimeSlotAction)(Table::all(), 19, 23);

        return view('dashboard', compact('users', 'days', 'tables', 'afternoonTables', 'eveningTables'));
    }
}
