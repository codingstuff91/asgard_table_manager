<?php

use App\Actions\UserSubscriptionAction;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\get;

it('Should display all categories in the create view form', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();
    $response = $this->get(route('table.create', $day));

    $response->assertOk();

    foreach (Category::all() as $category) {
        $response->assertSee($category->name);
    }
});

it('can create a new table', function () {
    $this->seed();
    $this->actingAs(User::first());

    mockHttpClient();

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 5,
        'total_points' => 1000,
        'start_hour' => '21:00',
    ]);

    expect($response)->toBeRedirect(route('days.show', Day::first()->id));

    expect(['organizer_id' => User::first()->id])->toBeInDatabase('tables');

    expect(['user_id' => User::first()->id])->toBeInDatabase('table_user');
});

it('can not create a table twice', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();

    // Try to create a new table once
    $response = $this->post(route('table.store', $day), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 5,
        'total_points' => 1000,
        'start_hour' => '21:00',
    ]);

    expect(Table::count())->toBe(2);

    // Try to create the same table twice
    $response = $this->post(route('table.store', $day), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 5,
        'total_points' => 1000,
        'start_hour' => '21:00',
    ]);

    expect(Table::count())->toBe(2)
        ->and($response)
        ->toBeRedirect(route('days.show', Day::first()->id))
        ->toHaveSession('error');

});

it('can update a table successfully', function () {
    $this->seed();
    $this->actingAs(User::first());

    mockHttpClient();

    $response = $this->patch(route('table.update', Table::first()->id), [
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 5,
        'total_points' => 1000,
        'start_hour' => '21:00',
    ]);

    expect($response)->toBeRedirect(route('days.show', Day::first()->id));
});

it('can not create a table without giving a number of players', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'total_points' => 1000,
        'start_hour' => '21:00',
    ]);

    expect($response)->toHaveInvalid('players_number');
});

it('can not create a table without giving a start hour', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'total_points' => 1000,
        'players_number' => 5,
    ]);

    expect($response)->toHaveInvalid('start_hour');
});

it('can not create a table without selecting a game', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'players_number' => 5,
        'total_points' => 1000,
        'start_hour' => '21:00',
    ]);

    expect($response)->toHaveInvalid('game_id');
});

it('can not create a table without defining the total points', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'players_number' => 5,
        'start_hour' => '21:00',
    ]);

    expect($response)->toHaveInvalid('total_points');
});

it('can subscribe a user to a table', function () {
    $this->seed();
    login();

    expect(Table::first()->users->count())->toBe(1);

    $this->get(route('table.subscribe', Table::first()));

    expect(Table::first()->users->count())->toBe(2);
});

it('can unsubscribe a user of a table', function () {
    $this->seed();
    login();

    $table = Table::first();

    expect(Table::first()->users()->count())->toBe(1);

    $this->get(route('table.unsubscribe', Table::first()));

    expect(Table::first()->users()->count())->toBe(1);
});

it('can not subscribe a user who is already subscribed to another table with the same start hour for the current day', function () {
    $this->seed();
    login();

    app(UserSubscriptionAction::class)->execute(Table::first(), Auth::user());

    $anotherTableAtSameHour = Table::factory()
        ->for(Game::factory())
        ->for(Day::first())
        ->for(Category::first())
        ->create([
            'organizer_id' => User::factory(),
            'start_hour' => '21:00',
        ]);

    $response = get(route('table.subscribe', $anotherTableAtSameHour));

    $response->assertRedirect(route('days.show', Table::first()->day));

    expect($anotherTableAtSameHour->users->count())->toBe(0);
});

test('a user can not delete a table he didnt created', function () {
    $this->seed();

    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('days.show', Day::first()->id));
    $response->assertOk();

    $response->assertDontSee('img/delete.png');
});

test('an admin user can delete a table he didnt created', function () {
    $this->seed();

    $this->actingAs(User::first());

    $response = $this->get(route('days.show', Day::first()->id));
    $response->assertOk();

    $response->assertSee('img/delete.png');
});

test('a user can delete a table', function () {
    $this->seed();
    $this->actingAs(User::first());

    mockHttpClient();

    expect(Table::all()->count())->toBe(1);

    $this->delete(route('table.delete', Table::first()));

    expect(Table::all()->count())->toBe(0);
});

test('a user could not subscribe to a table if the max number of players is reached', function () {
    Event::fake();

    $this->seed();

    $user = User::first();
    $anotherUser = User::factory()->create();

    $this->actingAs($user);

    $table = Table::first();
    $day = Day::first();

    $response = $this->get(route('table.subscribe', [$table, $user]));
    expect($table->users()->count())->toBe(2);

    // The user can not subscribe to a table and is redirected to the correct day table with an error message
    $this->actingAs($anotherUser);
    $response = $this->get(route('table.subscribe', [$table, $anotherUser]));

    expect($response)->toBeRedirect(route('days.show', [$day]));
});
