<?php

use App\Models\Day;

function createDay(): Day
{
    return Day::factory()->create();
}

function createPastDay(): Day
{
    return Day::factory()->create([
        'date' => now()->sub('day', 1),
    ]);
}
