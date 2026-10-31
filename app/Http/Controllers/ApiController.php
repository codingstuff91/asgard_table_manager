<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function index() {

        $tables = Table::select([
            'id',
            'day_id',
            'game_id',
            'start_hour',
            'description',
        ])
        ->whereHas('day', function ($query) {
            $query->whereBetween('date', [
                Carbon::today(),
                Carbon::today()->addDays(7)->endOfDay()
            ]);
        })
        ->with([
            'day:id,date',
            'game:id,name,category_id',
            'game.category:id,name',
        ])
        ->get();

        $tables->each(function ($table) {
            $table->day->date_formatted = $table->day->date->format('d/m/Y');
        });

        return response()->json($tables);
    }
}
