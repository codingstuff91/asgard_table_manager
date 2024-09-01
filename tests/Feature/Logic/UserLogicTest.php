<?php

use App\Actions\UserSubscriptionAction;
use App\Logic\UserLogic;

test('it should return true if an user is subscribed to a table with the same start hour for the current day', function () {
    login();
    $day = createDay();

    $table = createTable(day: $day, start_hour: '21:00');

    app(UserSubscriptionAction::class)->execute($table);

    $anotherTableAtSameHour = createTable(day: $day, start_hour: '21:00');

    $hasAlreadySubscribed = app(UserLogic::class)
        ->hasSubscribedToAnotherTableWithTheSameStartHour($day, $anotherTableAtSameHour);

    expect($table->users->count())
        ->toBeOne()
        ->and($hasAlreadySubscribed)
        ->toBeTrue();
});

test('it should return false if an user is not already subscribed to another table with the same start hour for the current day', function () {
    login();
    $day = createDay();

    $table = createTable(day: $day, start_hour: '21:00');

    $hasAlreadySubscribed = app(UserLogic::class)
        ->hasSubscribedToAnotherTableWithTheSameStartHour($day, $table);

    expect($hasAlreadySubscribed)->toBeFalse();
});
