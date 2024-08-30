<?php

use App\Actions\UserSubscriptionAction;
use App\Logic\UserLogic;
use App\Models\Category;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('it should return true if an user is subscribed to a table with the same start hour for the current day', function () {
    login();
    $day = createDay();

    $table = Table::factory()
        ->for(Category::factory())
        ->for(Game::factory())
        ->for($day)
        ->create([
            'organizer_id' => Auth::user()->id,
            'start_hour' => '21:00',
        ]);

    app(UserSubscriptionAction::class)->execute($table);

    $anotherTableAtSameHour = Table::factory()
        ->for(Game::factory())
        ->for($day)
        ->for(Category::first())
        ->has(User::factory())
        ->create([
            'organizer_id' => User::first()->id,
            'start_hour' => '21:00',
        ]);

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

    $table = Table::factory()
        ->for(Category::factory())
        ->for(Game::factory())
        ->for($day)
        ->create([
            'organizer_id' => Auth::user()->id,
            'start_hour' => '21:00',
        ]);

    $hasAlreadySubscribed = app(UserLogic::class)
        ->hasSubscribedToAnotherTableWithTheSameStartHour($day, $table);

    expect($hasAlreadySubscribed)->toBeFalse();
});
