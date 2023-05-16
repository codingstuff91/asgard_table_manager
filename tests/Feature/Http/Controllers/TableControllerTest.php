<?php

use Illuminate\Support\Facades\Event;

test('The create table form displays all categories', function () {
    $this->seed();
    $this->actingAs(\App\Models\User::first());

    $day = \App\Models\Day::first();
    $response = $this->get(route('table.create', $day));

    $response->assertOk();

    foreach (\App\Models\Category::all() as $category) {
        $response->assertSee($category->name);
    }
});

test('a table is created successfully', function () {
    Event::fake();

    $this->seed();
    $this->actingAs(\App\Models\User::first());

    $response = $this->post(route('table.store', \App\Models\Day::first()->id), [
        'organizer_id'   => \App\Models\User::first()->id,
        'day_id'         => \App\Models\Day::first()->id,
        'game_id'        => \App\Models\Game::first()->id,
        'players_number' => 5,
        'total_points'   => 1000,
        'start_hour'     => "21:00",
    ]);

    expect($response)->toBeRedirect(route('days.show', \App\Models\Day::first()->id));

    expect(['organizer_id' => \App\Models\User::first()->id])->toBeInDatabase('tables');

    Event::assertDispatched(App\Events\TableCreated::class);
});

test('a table is updated successfully', function () {
    Event::fake();

    $this->seed();
    $this->actingAs(\App\Models\User::first());

    $response = $this->post(route('table.update', \App\Models\Day::first()->id), [
        'day_id'         => \App\Models\Day::first()->id,
        'game_id'        => \App\Models\Game::first()->id,
        'players_number' => 5,
        'total_points'   => 1000,
        'start_hour'     => "21:00",
    ]);

    expect($response)->toBeRedirect(route('days.show', \App\Models\Day::first()->id));

    expect(['organizer_id' => \App\Models\User::first()->id])->toBeInDatabase('tables');

    Event::assertDispatched(App\Events\TableUpdated::class);
});

test('a table can not be created without defining a number of players', function () {
    $this->seed();
    $this->actingAs(\App\Models\User::first());

    $response = $this->post(route('table.store', \App\Models\Day::first()->id), [
        'organizer_id'   => \App\Models\User::first()->id,
        'day_id'         => \App\Models\Day::first()->id,
        'game_id'        => \App\Models\Game::first()->id,
        'total_points'   => 1000,
        'start_hour'     => "21:00",
    ]);

    expect($response)->toHaveInvalid('players_number');
});

test('a table can not be created without defining a start hour', function () {
    $this->seed();
    $this->actingAs(\App\Models\User::first());

    $response = $this->post(route('table.store', \App\Models\Day::first()->id), [
        'organizer_id'   => \App\Models\User::first()->id,
        'day_id'         => \App\Models\Day::first()->id,
        'game_id'        => \App\Models\Game::first()->id,
        'total_points'   => 1000,
        'players_number' => 5,
    ]);

    expect($response)->toHaveInvalid('start_hour');
});

test('a table can not be created without selecting a game', function () {
    $this->seed();
    $this->actingAs(\App\Models\User::first());

    $response = $this->post(route('table.store', \App\Models\Day::first()->id), [
        'organizer_id'   => \App\Models\User::first()->id,
        'day_id'         => \App\Models\Day::first()->id,
        'players_number' => 5,
        'total_points'   => 1000,
        'start_hour'     => "21:00",
    ]);

    expect($response)->toHaveInvalid('game_id');
});

test('a table can not be created without defining the total points', function () {
    $this->seed();
    $this->actingAs(\App\Models\User::first());

    $response = $this->post(route('table.store', \App\Models\Day::first()->id), [
        'organizer_id'   => \App\Models\User::first()->id,
        'day_id'         => \App\Models\Day::first()->id,
        'players_number' => 5,
        'start_hour'     => "21:00",
    ]);

    expect($response)->toHaveInvalid('total_points');
});

test('a user is subscribed to a table', function () {
    Event::fake();

    $this->seed();

    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $table = \App\Models\Table::first();

    expect($table->users()->count())->toBe(1);

    $this->get(route('table.subscribe',[$table, $user]));

    expect($table->users()->count())->toBe(2);

    Event::assertDispatched(App\Events\UserTableSubscribed::class);
});

test('a user is unsubscribed of a table', function () {
    $this->seed();

    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $table = \App\Models\Table::first();

    expect($table->users()->count())->toBe(1);

    $this->get(route('table.subscribe',[$table, $user]));

    expect($table->users()->count())->toBe(2);

    $this->get(route('table.unsubscribe',[$table, $user]));

    expect($table->users()->count())->toBe(1);
});

test('a user can not delete a table he didnt created', function () {
    $this->seed();

    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('days.show', \App\Models\Day::first()->id));
    $response->assertOk();

    $response->assertDontSee("img/delete.png");
});

test('an admin user can delete a table he didnt created', function () {
    $this->seed();

    $this->actingAs(\App\Models\User::first());

    $response = $this->get(route('days.show', \App\Models\Day::first()->id));
    $response->assertOk();

    $response->assertSee("img/delete.png");
});

test('a user can delete a table', function () {
    $this->seed();

    $this->actingAs(\App\Models\User::first());

    expect(\App\Models\Table::all()->count())->toBe(1);

    $this->delete(route('table.delete', \App\Models\Table::first()));

    expect(\App\Models\Table::all()->count())->toBe(0);
});
