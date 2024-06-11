<?php

use App\Models\Day;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('The index page is rendered correctly', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->get(route('days.index'));

    expect($response)->toBeOk();
});

test('The create page is rendered correctly', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->get(route('days.create'));

    expect($response)->toBeOk();
});

test('The show page is rendered correctly', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->get(route('days.show', \App\Models\Day::first()->id));

    expect($response)->toBeOk();
});

test('A day can not be created twice', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('days.store'), [
        'date' => now(),
    ]);

    expect($response)->toHaveInvalid(['date']);
});

test('A day can not be created without choosing a date', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('days.store'), [
        'date' => '',
    ]);

    expect($response)->toHaveInvalid(['date']);
});

test('A day is created successfully', function () {
    $this->seed();
    $this->actingAs(User::first());

    $date = now()->add('day', 1);

    $response = $this->post(route('days.store'), [
        'date' => $date,
    ]);

    expect($response)->toHaveValid(['date'])
        ->and($response)->toBeRedirect(route('days.index'))
        ->and([
            'date' => $date,
        ])->toBeInDatabase(table: 'days');
});

test('The past days are hidden from index page', function () {
    $this->seed();
    $this->actingAs(User::first());

    $pastDay = Day::factory()->create([
        'date' => now()->sub('day', 1),
    ]);

    $response = $this->get(route('days.index'));

    expect($response)
        ->toBeOk()
        ->not->toContainText($pastDay->date->format('d/m/Y'));
});

test('the edit warning page is rendered correctly', function () {
    $this->seed();

    $day = Day::first();

    actingAs(User::first());

    $response = get(route('days.warning', $day));

    expect($response)->toBeOk();
});

test('the warning could not be store without an explanation', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();

    $response = $this->patch(route('days.confirm_warning', $day), [
        'explanation' => '',
    ]);

    expect($response)->toHaveInvalid(['explanation']);
});

test('A warning message can be stored', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();
    $explanationTest = 'Example of explanation';

    $response = $this->patch(route('days.confirm_warning', $day), [
        'explanation' => $explanationTest,
    ]);

    expect($day->refresh()->explanation)->toBe($explanationTest)
        ->and($response)->toBeRedirect(route('days.index'));
});
