<?php

use App\Actions\Table\CountTablesForTimeSlotAction;
use App\Models\Table;

const START_TIME_AFTERNOON = 13;
const END_TIME_AFTERNOON = 19;
const START_TIME_EVENING = 19;
const END_TIME_EVENING = 23;

beforeEach(function () {
    login();
});

test('It counts the afternoon tables', function () {
    // Create 2 afternoon tables
    createTable(start_hour: '13:00');
    createTable(start_hour: '17:00');

    // Create tables out of afternoon time slot
    createTable(start_hour: '19:00');

    $afternoonTablesCount = app(CountTablesForTimeSlotAction::class)(Table::all(), START_TIME_AFTERNOON,
        END_TIME_AFTERNOON);

    expect($afternoonTablesCount)->toBe(2);
});

test('It counts the evening tables', function () {
    // Create 3 evening tables
    createTable(start_hour: '19:00');
    createTable(start_hour: '20:00');
    createTable(start_hour: '21:00');

    // Create tables out of evening time slot
    createTable(start_hour: '18:00');

    $eveningTablesCount = app(CountTablesForTimeSlotAction::class)(Table::all(), START_TIME_EVENING, END_TIME_EVENING);

    expect($eveningTablesCount)->toBe(3);
});
