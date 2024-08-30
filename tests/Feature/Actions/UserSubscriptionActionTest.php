<?php

use App\Actions\UserSubscriptionAction;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;

test('The action class does its job correctly', function () {
    login();

    $table = Table::factory()
        ->for(Category::factory())
        ->for(Game::factory())
        ->for(Day::factory())
        ->create([
            'organizer_id' => Auth::user()->id,
        ]);

    app(UserSubscriptionAction::class)->execute($table);

    expect($table->users->count())->toBeOne();
});
