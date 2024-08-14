<?php

use App\Models\Day;
use App\Models\Event;
use App\Models\User;

it('can render the event create page', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();
    $response = $this->get(route('event.create', $day));

    $response->assertOk();
});

it('stores a new event', function () {
    $this->seed();
    $this->actingAs(User::first());

    mockHttpClient();

    $day = Day::first();
    $response = $this->post(route('event.store', $day), [
        'name' => 'example',
        'description' => 'description',
        'start_hour' => '14:00',
    ]);

    expect(['name' => 'example'])->toBeInDatabase('events')
        ->and(['description' => 'description'])->toBeInDatabase('events')
        ->and($response)
        ->toBeRedirect(route('days.show', Day::first()->id));
});

it('can not create an event without a name', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('event.store', Day::first()->id), [
        'name' => '',
        'description' => 'description',
    ]);

    expect($response)->toHaveInvalid('name');
});

it('can not create an event without a description', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('event.store', Day::first()->id), [
        'name' => 'example',
        'description' => '',
    ]);

    expect($response)->toHaveInvalid('description');
});

it('can not create an event without a start_hour', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->post(route('event.store', Day::first()->id), [
        'name' => 'example',
        'description' => 'description',
        'start_hour' => '',
    ]);

    expect($response)->toHaveInvalid('start_hour');
});

it('can render the event edit page', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->get(route('event.edit', Event::first()));

    $response->assertOk();
});

it('can update an event successfully', function () {
    $this->seed();
    $this->actingAs(User::first());

    mockHttpClient();

    $response = $this->put(route('event.update', Event::first()), [
        'name' => 'edited',
        'description' => 'description',
        'start_hour' => '21:00',
    ]);

    $eventUpdated = Event::first();

    expect($eventUpdated->name)->toBe('edited')
        ->and($eventUpdated->description)->toBe('description')
        ->and($eventUpdated->start_hour)->toBe('21:00')
        ->and($response)->toBeRedirect(route('days.show', Day::first()->id));

});

it('can subscribe a user to an event', function () {
    $this->seed();
    login();

    expect(Event::first()->users->count())->toBe(1);

    $this->get(route('event.subscribe', Event::first()));

    expect(Event::first()->users->count())->toBe(2);
});

it('can unsubscribe a user of an event', function () {
    $this->seed();
    login();

    $event = Event::first();

    expect(Event::first()->users()->count())->toBe(1);

    $this->get(route('event.unsubscribe', Event::first()));

    expect(Event::first()->users()->count())->toBe(1);
});

it('deletes an event', function () {
    $this->seed();
    login();

    mockHttpClient();

    $response = $this->delete(route('event.destroy', Event::first()));

    expect(Event::count())->toBe(0)
        ->and($response)->toBeRedirect(route('days.show', Day::first()->id));
});
