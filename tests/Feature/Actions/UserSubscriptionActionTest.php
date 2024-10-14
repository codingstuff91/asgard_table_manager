<?php

use App\Actions\UserSubscriptionAction;

test('The action class does its job correctly', function () {
    login();

    $table = createTable();

    app(UserSubscriptionAction::class)->execute($table);

    expect($table->users->count())->toBe(1);
});
