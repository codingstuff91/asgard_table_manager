<?php

use App\Models\Day;
use App\Models\Game;
use App\Models\User;
use App\Models\Table;
use App\Logic\UserLogic;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Actions\UserSubscriptionAction;

test('it should return true if an user is subscribed to a table with the same start hour for the current day', function () {
    $this->seed();
    login();

    app(UserSubscriptionAction::class)->execute(Table::first(), Auth::user());

    $anotherTableAtSameHour = Table::factory()
        ->for(Game::factory())
        ->for(Day::first())
        ->for(Category::first())
        ->has(User::factory())
        ->create([
            'organizer_id' => User::first()->id,
            'start_hour' => '21:00',
        ]);

    $hasAlreadySubscribed = app(UserLogic::class)
        ->hasSubscribedToAnotherTableWithTheSameStartHour(Day::first(), $anotherTableAtSameHour);

    expect(Table::first()->users->count())
        ->toBe(2)
        ->and($hasAlreadySubscribed)->toBe(true);
});

test('it should return false if an user is not already subscribed to another table with the same start hour for the current day', function () {
    $this->seed();
    login();

    $hasAlreadySubscribed = app(UserLogic::class)
        ->hasSubscribedToAnotherTableWithTheSameStartHour(Day::first(), Table::first());

    expect($hasAlreadySubscribed)->toBe(false);
});
