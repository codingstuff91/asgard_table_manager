<?php

namespace App\Commands;

use App\Http\Requests\TableStoreRequest;
use App\Models\Day;
use App\Models\Game;

class CreateTableCommand
{
    public function __construct(
        public Day $day,
        public Game $game,
        public TableStoreRequest $request)
    {
        //
    }
}
