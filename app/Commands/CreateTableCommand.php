<?php

namespace App\Commands;

use App\Http\Requests\TableStoreRequest;
use App\Models\Day;

class CreateTableCommand
{
    public function __construct(public Day $day, public TableStoreRequest $request)
    {
        //
    }
}
