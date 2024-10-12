<?php

use App\Models\Event;
use Tests\RequestFactories\EventRequestFactory;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

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
    login();

    $response = $this->get(route('event.edit', createEvent()));

    $response->assertOk();
});

it('can update an event successfully', function () {
    mockHttpClient();
    login();

    $eventAttributes = EventRequestFactory::new()
        ->withName('edited name')
        ->withDescription('edited description')
        ->withStartHour('21:00')
        ->create();

    $event = createEvent();

    $response = put(route('event.update', $event), $eventAttributes);
    expect($response)->toBeRedirect(route('days.show', $event->day->id));

    assertDatabaseHas('events', $eventAttributes);
});

it('can subscribe a user to an event', function () {
    login();
    $event = createEvent();

    get(route('event.subscribe', $event));

    expect($event->users->count())->toBe(1);
});

it('can unsubscribe a user of an event', function () {
    login();
    $event = createEvent();

    get(route('event.subscribe', $event));
    expect($event->users->count())->toBe(1);

    get(route('event.unsubscribe', $event));
    expect($event->refresh()->users->count())->toBe(0);
});

it('deletes an event', function () {
    login();
    mockHttpClient();
    $event = createEvent();

    $response = delete(route('event.destroy', $event));

    expect(Event::count())
        ->toBe(0)
        ->and($response)
        ->toBeRedirect(route('days.show', $event->day));
});
