<?php

use App\Models\Association;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Storages\AssociationStorage;
use Illuminate\Support\Facades\Auth;
use Tests\RequestFactories\TableRequestFactory;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

beforeEach(function () {
    login();
});

it('Display the game categories of the current association in the create view form', function () {
    $association = Association::factory()->create();
    AssociationStorage::put($association);

    $day = createDayForAssociation(AssociationStorage::current(), now());
    $category = Category::factory()->create();

    $response = get(route('table.create', $day));

    $response->assertOk();

    $response->assertSee($category->name);
});

it('Do not Display the game categories of another association in the create view form', function () {
    $association = Association::factory()->create();
    AssociationStorage::put($association);

    $day = createDayForAssociation(AssociationStorage::current(), now());
    $category = Category::factory()->create();

    // Create another category for another Association
    $anotherAssociation = Association::factory()->create();
    $anotherCategory = Category::factory()->create([
        'association_id' => $anotherAssociation->id,
    ]);

    $response = get(route('table.create', $day));
    $response->assertSee($category->name);
    $response->assertDontSee($anotherCategory->name);
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
        ->and(Table::count())->toBe(1);

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

    expect(Table::count())->toBe(1)
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

test('Deletes a table', function () {
    mockHttpClient();

    $table = createTable();

    expect(Table::count())->toBe(1);

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
