<?php

use App\Actions\UserSubscriptionAction;
use App\Models\Table;
use App\Models\User;

test('The action class does its job correctly', function () {
    $this->seed();

    $this->actingAs(User::first());

    $table = Table::first();
    $user = User::first();

    app(UserSubscriptionAction::class)->execute($table, $user);

    expect($table->users->count())->toBe(2);
});
