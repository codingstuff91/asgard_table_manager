<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    login();
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
