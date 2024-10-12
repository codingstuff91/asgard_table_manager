<?php

use App\Models\Day;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

beforeEach(function () {
    mockHttpClient();
    login();
});

test('The index page is rendered correctly', function () {
    $response = get(route('days.index'));

    expect($response)->toBeOk();
});

test('The create page is rendered correctly', function () {
    $response = get(route('days.create'));

    expect($response)->toBeOk();
});

test('The show page is rendered correctly', function () {
    $day = createDay();

    $response = get(route('days.show', $day));

    expect($response)->toBeOk();
});

test('A day can not be created twice', function () {
    createDay();

    $response = $this->post(route('days.store'), [
        'date' => now(),
    ]);

    expect($response)->toHaveInvalid(['date']);
});

test('A day can not be created without choosing a date', function () {
    $response = $this->post(route('days.store'), [
        'date' => '',
    ]);

    expect($response)->toHaveInvalid(['date']);
});

test('A day is created successfully', function () {
    $date = now();

    post(route('days.store'), [
        'date' => $date,
    ]);

    assertDatabaseHas('days', [
        'date' => $date,
    ]);
});

test('The past days are hidden from index page', function () {
    $pastDay = createPastDay();

    $response = get(route('days.index'));

    expect($response)
        ->not
        ->toContainText($pastDay->date->format('d/m/Y'));
});

test('the edit warning page is rendered correctly', function () {
    $day = createDay();

    $response = get(route('days.warning', $day));

    expect($response)->toBeOk();
});

test('the warning could not be store without an explanation', function () {
    $day = createDay();

    $response = patch(route('days.confirm_warning', $day), [
        'explanation' => '',
    ]);

    expect($response)->toHaveInvalid(['explanation']);
});

test('A warning message can be stored', function () {
    $day = createDay();

    $response = patch(route('days.confirm_warning', $day), [
        'explanation' => 'Example of explanation',
    ]);

    expect($day->refresh()->explanation)
        ->toBe('Example of explanation')
        ->and($response)->toBeRedirect(route('days.index'));
});

test('The warning message is visible on the show page if exists', function () {
    $day = createDay();

    patch(route('days.confirm_warning', $day), [
        'explanation' => 'Example of explanation',
    ]);

    $response = get(route('days.show', $day));

    expect($response)->toContainText('Example of explanation');
});

test('The warning message is hidden when it doesnt exists', function () {
    $day = createDay();
    $explanationTest = 'Example of explanation';

    $response = get(route('days.show', $day));

    expect($response)->not()->toContainText($explanationTest)
        ->and($response)->not()->toContainText("<h3 class='my-4 text-white text-center w-full bg-red-500 rounded-lg'>");
});

test('the cancel page is rendered correctly', function () {
    $day = createDay();
    $response = get(route('days.cancel', $day));

    expect($response)->toBeOk();
});

test('the day cancelation could not be executed without an explanation', function () {
    $day = createDay();

    $response = patch(route('days.confirm_cancel', $day), [
        'explanation' => '',
    ]);

    expect($response)->toHaveInvalid(['explanation']);
});

test('The cancelation message is stored', function () {
    $day = createDay();

    patch(route('days.confirm_cancel', $day), [
        'explanation' => 'Example of explanation',
    ]);

    expect($day->refresh())
        ->explanation
        ->toBe('Example of explanation');
});

test('The cancelation of a day must block the ability to create table', function () {
    $day = createDay();

    patch(route('days.confirm_cancel', $day), [
        'explanation' => 'Example of explanation',
    ]);

    expect($day->refresh())
        ->can_create_table
        ->toBe(0);
});

test('The cancellation of a day must delete every tables created', function () {
    $day = createDay();

    patch(route('days.confirm_cancel', $day), [
        'explanation' => 'Example of explanation',
    ]);

    expect($day->tables->count())->toBe(0);
});

test('The cancellation message is visible on the show page if exists', function () {
    $day = createDay();

    patch(route('days.confirm_cancel', $day), [
        'explanation' => 'Example of explanation',
    ]);

    $response = get(route('days.show', $day));

    expect($response)->toContainText('Example of explanation');
});

test('The action buttons are visible for admin users on days index page', function () {
    loginAdmin();
    Day::factory()->create();

    get(route('days.index'))
        ->assertSee('img/cancel.png')
        ->assertSee('img/warning.png');
});

test('The action buttons are hidden for non admin users on days index page', function () {
    get(route('days.index'))
        ->assertDontSee('img/cancel.png')
        ->assertDontSee('img/warning.png');
});

test('The create buttons are hidden for a cancelled day', function () {
    $day = createDay();

    patch(route('days.confirm_cancel', $day), [
        'explanation' => 'Example of explanation',
    ]);

    get(route('days.show', $day))
        ->assertDontSee('img/game-table.png')
        ->assertDontSee('img/calendar.png');
});
