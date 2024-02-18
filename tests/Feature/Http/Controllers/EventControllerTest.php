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
