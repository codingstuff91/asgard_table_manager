<?php

namespace App\Http\Controllers;

use App\Actions\Table\CountTablesForTimeSlotAction;
use App\Models\Table;
use App\Storages\AssociationStorage;
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
        $currentAssociation = AssociationStorage::current();

        $users = $currentAssociation->users->count();
        $days = $currentAssociation->days->count();

        $tables = Table::query()->whereHas('day', function ($day) use ($currentAssociation) {
            $day->where('association_id', $currentAssociation->id);
        })->get();

        $tablesCount = $tables->count();

        $afternoonTables = ($this->countTablesForTimeSlotAction)($tables, 13, 19);
        $eveningTables = ($this->countTablesForTimeSlotAction)($tables, 19, 23);

        return view('dashboard', compact('users', 'days', 'tablesCount', 'afternoonTables', 'eveningTables'));
    }
}
