<?php

use App\Actions\UserSubscriptionAction;
use App\Models\Category;
use App\Models\Day;
use App\Models\Game;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

use function Pest\Laravel\get;

it('can render the event create page', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();
    $response = $this->get(route('event.create', $day));

    $response->assertOk();
});

it('can create a new event', function () {
    $this->seed();
    $this->actingAs(User::first());

    $day = Day::first();
    $response = $this->post(route('event.store', $day), [
        'name' => 'example',
        'description' => 'description',
        'start_hour' => '14:00',
    ]);

    expect(['name' => 'example'])->toBeInDatabase('events');
    expect(['description' => 'description'])->toBeInDatabase('events');
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

it('can update a table successfully', function () {
    $this->seed();
    $this->actingAs(User::first());

    $response = $this->put(route('event.update', Event::first()), [
        'name' => 'edited',
        'description' => 'description',
        'start_hour' => '21:00',
    ]);

    $eventUpdated = Event::first();

    expect($eventUpdated->name)->toBe('edited')
        ->and($eventUpdated->description)->toBe('description')
        ->and($eventUpdated->start_hour)->toBe("21:00")
        ->and($response)->toBeRedirect(route('days.show', Day::first()->id));

});
