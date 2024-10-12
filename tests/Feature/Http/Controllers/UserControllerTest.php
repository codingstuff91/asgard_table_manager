<?php

use App\Actions\UserSubscriptionAction;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\get;

beforeEach(function () {
    login();
});

it('Subscribes a user to a table', function () {
    mockHttpClient();

    $table = createTable();

    get(route('table.subscribe', $table));

    expect($table->users->count())->toBe(1);
});

it('Unsubscribes a user of a table', function () {
    mockHttpClient();

    $table = createTable();

    get(route('table.unsubscribe', $table));

    expect($table->users()->count())->toBe(0);
});

it('Can not subscribe a user already subscribed to another table with the same start hour for the same day',
    function () {
        mockHttpClient();
        $day = createDay();
        $table = createTable(day: $day, start_hour: '21:00');

        app(UserSubscriptionAction::class)->execute($table, Auth::user());

        $anotherTableAtSameHour = createTable(day: $day, start_hour: '21:00');

        $response = get(route('table.subscribe', $anotherTableAtSameHour));

        $response->assertRedirect(route('days.show', $anotherTableAtSameHour->day));

        expect($anotherTableAtSameHour->users->count())->toBe(0);
    });

it('Can not subscribe a user twice or more for the same table', function () {
    $day = createDay();
    $table = createTable(day: $day, start_hour: '21:00');

    app(UserSubscriptionAction::class)->execute($table, Auth::user());

    $response = get(route('table.subscribe', $table));

    expect($response)
        ->toBeRedirect(route('days.show', $day))
        ->toHaveSession('error');
});
