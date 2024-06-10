<?php

namespace App\Commands;

use App\Http\Requests\TableStoreRequest;
use App\Models\Table;

class UpdateTableCommand
{
    public function __construct(
        public Table $table,
        public TableStoreRequest $request
    ) {
        //
    }
}
