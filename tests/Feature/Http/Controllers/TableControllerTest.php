<?php

use App\Actions\UserSubscriptionAction;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\RequestFactories\TableRequestFactory;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

beforeEach(function () {
    login();
});

it('Display all game categories in the create view form', function () {
    $day = createDay();
    $category = Category::factory()->create();

    $response = get(route('table.create', $day));

    $response->assertOk();

    $response->assertSee($category->name);
});

it('Creates a new table', function () {
    $day = createDay();
    $game = createGameWithCategory();
    mockHttpClient();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame($game)
        ->withCategory($game->category)
        ->withPlayersNumber(5)
        ->withStartHour('21:00')
        ->create();

    $response = post(route('table.store', $day), $tableAttributes);

    expect($response)
        ->toBeRedirect(route('days.show', $day))
        ->and(Table::count())->toBeOne();

    assertDatabaseHas('tables', $tableAttributes);
});

it('Can not create a table without a start hour', function () {
    $day = createDay();
    $game = createGameWithCategory();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame($game)
        ->withCategory($game->category)
        ->withPlayersNumber(5)
        ->withStartHour('')
        ->create();

    $response = post(route('table.store', Day::first()->id), $tableAttributes);

    expect($response)->toHaveInvalid('start_hour');
});

it('Can not create a table without a game', function () {
    $day = createDay();
    $game = createGameWithCategory();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame(null)
        ->withCategory($game->category)
        ->withPlayersNumber(5)
        ->withStartHour('')
        ->create();

    $response = $this->post(route('table.store', $day->id), $tableAttributes);

    expect($response)->toHaveInvalid('game_id');
});

it('Can not create a table without the number of players', function () {
    $day = createDay();
    $game = createGameWithCategory();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame($game)
        ->withCategory($game->category)
        ->withPlayersNumber(null)
        ->withStartHour('14:00')
        ->create();

    $response = $this->post(route('table.store', $day->id), $tableAttributes);

    expect($response)->toHaveInvalid('players_number');
});

it('Can not create the same table twice', function () {
    login();
    $day = createDay();
    $game = createGameWithCategory();
    mockHttpClient();

    $tableAttributes = TableRequestFactory::new()
        ->withOrganizer(Auth::user())
        ->withDay($day)
        ->withGame($game)
        ->withCategory($game->category)
        ->withPlayersNumber(5)
        ->withStartHour('21:00')
        ->create();

    // Create a new table
    post(route('table.store', $day), $tableAttributes);

    // Try to create the same table twice
    $response = post(route('table.store', $day), $tableAttributes);

    expect(Table::count())->toBeOne()
        ->and($response)
        ->toBeRedirect(route('days.show', $day->id))
        ->toHaveSession('error');
});

it('Updates a table', function () {
    login();
    mockHttpClient();

    $table = createTable();

    $response = patch(route('table.update', $table), [
        'day_id' => Day::first()->id,
        'game_id' => Game::first()->id,
        'category_id' => Category::first()->id,
        'players_number' => 3,
        'start_hour' => '15:00',
    ]);

    $tableUpdated = Table::first();

    expect($tableUpdated->game_id)->toBe('1')
        ->and($tableUpdated->players_number)->toBe(3)
        ->and($tableUpdated->start_hour)->toBe('15:00')
        ->and($response)->toBeRedirect(route('days.show', Day::first()->id));

});

it('Subscribes a user to a table', function () {
    mockHttpClient();

    $table = createTable();

    get(route('table.subscribe', $table));

    expect($table->users->count())->toBeOne();
});

it('Unsubscribes a user of a table', function () {
    mockHttpClient();

    $table = createTable();

    get(route('table.unsubscribe', $table));

    expect($table->users()->count())->toBe(0);
});

it('Can not subscribe a user already subscribed to another table with the same start hour for the same day', function () {
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

test('A user can not see the edit action button for a table he didnt created', function () {
    $day = createDay();

    $anotherUser = User::factory()->create();

    actingAs($anotherUser)
        ->get(route('days.show', $day))
        ->assertOk()
        ->assertDontSee('img/edit.png');
});

test('An admin user can see the edit action button for a table he didnt created', function () {
    loginAdmin();
    $day = createDay();

    createTable(day: $day);

    get(route('days.show', $day))
        ->assertOk()
        ->assertSee('img/edit.png');
});

test('A user can not render the edit page for a table he didnt create', function () {
    $table = createTable();

    $anotherUser = User::factory()->create();

    actingAs($anotherUser)
        ->get(route('table.edit', $table))
        ->assertForbidden();
});

test('An admin user can render the edit page for a table he didnt create', function () {
    loginAdmin();

    $table = createTable();

    get(route('table.edit', $table))->assertOk();
});

test('A user can not see the delete action button for a table he didnt created', function () {
    $day = createDay();

    $anotherUser = User::factory()->create();

    createTable(day: $day);

    actingAs($anotherUser)
        ->get(route('days.show', $day))
        ->assertOk()
        ->assertDontSee('img/delete.png');
});

test('An admin user can see the delete action button for a table he didnt created', function () {
    loginAdmin();
    $day = createDay();

    createTable(day: $day);

    get(route('days.show', $day))
        ->assertOk()
        ->assertSee('img/delete.png');
});

test('Deletes a table', function () {
    mockHttpClient();

    $table = createTable();

    expect(Table::count())->toBeOne();

    delete(route('table.delete', $table));

    expect(Table::count())->toBe(0);
});

test('Table creation is not allowed for a cancelled day', function () {
    $day = createDay();

    patch(route('days.confirm_cancel', $day), [
        'explanation' => 'Example of explanation',
    ]);

    $tableCreationResponse = get(route('table.create', $day));

    expect($tableCreationResponse)->toBeForbidden();
});
