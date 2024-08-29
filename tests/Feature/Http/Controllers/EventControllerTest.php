<?php

use App\Models\Day;
use App\Models\Event;
use App\Models\User;
use Tests\RequestFactories\EventRequestFactory;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('can render the event create page', function () {
    login();
    $day = createDay();

    $response = $this->get(route('event.create', $day));

    $response->assertOk();
});

it('stores a new event', function () {
    login();
    $day = createDay();
    mockHttpClient();

    $eventAttributes = EventRequestFactory::new()
        ->withName('example')
        ->withDescription('description')
        ->withStartHour('14:00')
        ->create();

    $response = post(route('event.store', $day),
        $eventAttributes
    );

    expect($response)->toBeRedirect(route('days.show', $day));
    assertDatabaseHas('events', $eventAttributes);
});

it('can not create an event without a name', function () {
    login();

    $eventAttributes = EventRequestFactory::new()
        ->withName('')
        ->withDescription('description')
        ->withStartHour('14:00')
        ->create();

    $response = post(route('event.store', createDay()), $eventAttributes);

    expect($response)->toHaveInvalid('name');
});

it('can not create an event without a description', function () {
    login();

    $eventAttributes = EventRequestFactory::new()
        ->withName('example')
        ->withDescription('')
        ->withStartHour('14:00')
        ->create();

    $response = post(route('event.store', createDay()), $eventAttributes);

    expect($response)->toHaveInvalid('description');
});

it('can not create an event without a start_hour', function () {
    login();

    $eventAttributes = EventRequestFactory::new()
        ->withName('example')
        ->withDescription('description')
        ->withStartHour('')
        ->create();

    $response = $this->post(route('event.store', createDay()), $eventAttributes);

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
