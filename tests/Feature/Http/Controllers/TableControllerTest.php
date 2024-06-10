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

it('Display all game categories in the create view form', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();
    $response = $this->get(route('table.create', $day));

    $response->assertOk();

    foreach (Category::all() as $category) {
        $response->assertSee($category->name);
    }
});

it('Creates a new table', function () {
    $this->seed();

    $this->actingAs(User::first());

    mockHttpClient();

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 5,
        'start_hour' => '21:00',
    ]);

    expect($response)
        ->toBeRedirect(route('days.show', Day::first()->id))
        ->and(Table::count())->toBe(2);
});

it('Can not create the same table twice', function () {
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
        'start_hour' => '21:00',
    ]);

    expect(Table::count())->toBe(2)
        ->and($response)
        ->toBeRedirect(route('days.show', Day::first()->id))
        ->toHaveSession('error');

})->skip();

it('Updates a table', function () {
    $this->seed();
    $this->actingAs(User::first());

    mockHttpClient();

    $response = $this->patch(route('table.update', Table::first()->id), [
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 5,
        'start_hour' => '21:00',
    ]);

    $tableUpdated = Table::first();

    expect($tableUpdated->game_id)->toBe('1')
        ->and($tableUpdated->players_number)->toBe(5)
        ->and($tableUpdated->start_hour)->toBe('21:00')
        ->and($response)->toBeRedirect(route('days.show', Day::first()->id));

});

it('Can not create a table without the number of players', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'start_hour' => '21:00',
    ]);

    expect($response)->toHaveInvalid('players_number');
});

it('Can not create a table without a start hour', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 5,
    ]);

    expect($response)->toHaveInvalid('start_hour');
});

it('Can not create a table without a game', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('table.store', Day::first()->id), [
        'organizer_id' => User::first()->id,
        'day_id' => Day::first()->id,
        'players_number' => 5,
        'start_hour' => '21:00',
    ]);

    expect($response)->toHaveInvalid('game_id');
});

it('Subscribes a user to a table', function () {
    $this->seed();
    login();

    expect(Table::first()->users->count())->toBe(1);

    $this->get(route('table.subscribe', Table::first()));

    expect(Table::first()->users->count())->toBe(2);
});

it('Unsubscribes a user of a table', function () {
    $this->seed();
    login();

    $table = Table::first();

    expect(Table::first()->users()->count())->toBe(1);

    $this->get(route('table.unsubscribe', Table::first()));

    expect(Table::first()->users()->count())->toBe(1);
});

it('Can not subscribe a user already subscribed to another table with the same start hour for the same day', function () {
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

test('A user can not see the edit action button for a table he didnt created', function () {
    $this->seed();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('days.show', Day::first()->id))
        ->assertOk()
        ->assertDontSee('img/edit.png');
});

test('An admin user can see the edit action button for a table he didnt created', function () {
    $this->seed();

    $adminUser = User::factory()->create([
        'admin' => true,
    ]);

    $this->actingAs($adminUser);

    $response = $this->get(route('days.show', Day::first()->id));

    $response
        ->assertOk()
        ->assertSee('img/edit.png');
});

test('A user can not render the edit page for a table he didnt create', function () {
    $this->seed();

    $anotherUser = User::factory()->create();

    $table = Table::first();

    $this
        ->actingAs($anotherUser)
        ->get(route('table.edit', $table))
        ->assertForbidden();
});

test('An admin user can render the edit page for a table he didnt create', function () {
    $this->seed();

    $adminUser = User::factory()->create([
        'admin' => true,
    ]);

    $table = Table::first();

    $this
        ->actingAs($adminUser)
        ->get(route('table.edit', $table))
        ->assertOk();
});

test('A user can not see the delete action button for a table he didnt created', function () {
    $this->seed();

    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(route('days.show', Day::first()->id))
        ->assertOk()
        ->assertDontSee('img/delete.png');
});

test('An admin user can see the delete action button for a table he didnt created', function () {
    $this->seed();

    $this
        ->actingAs(User::first())
        ->get(route('days.show', Day::first()->id))
        ->assertOk()
        ->assertSee('img/delete.png');
});

test('Deletes a table', function () {
    $this->seed();
    $this->actingAs(User::first());

    mockHttpClient();

    expect(Table::all()->count())->toBe(1);

    $this->delete(route('table.delete', Table::first()));

    expect(Table::all()->count())->toBe(0);
});

test('A user could not subscribe to a table if the max number of players is reached', function () {
    Event::fake();

    $this->seed();

    $user = User::first();
    $anotherUser = User::factory()->create();

    $table = Table::first();
    $day = Day::first();

    $this
        ->actingAs($user)
        ->get(route('table.subscribe', [$table, $user]));

    expect($table->users()->count())->toBe(2);

    // The user can not subscribe to a table and is redirected to the correct day table with an error message
    $response = $this
        ->actingAs($anotherUser)
        ->get(route('table.subscribe', [$table, $anotherUser]));

    expect($response)->toBeRedirect(route('days.show', [$day]));
});
