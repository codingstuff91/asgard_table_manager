<?php

use App\Models\Association;
use App\Models\Day;
use App\Storages\AssociationStorage;

use function Pest\Laravel\get;

beforeEach(function () {
    login();

    $association = Association::factory()->create();
    AssociationStorage::put($association);
});

test('It renders the index page', function () {
    $response = get(route('dashboard'));

    expect($response)->toBeOk();
});

test('It shows the correct Days count on index page', function () {
    createDayForAssociation(AssociationStorage::current(), now());
    createDayForAssociation(AssociationStorage::current(), now()->addDay()->format('Y-m-d'));

    $response = get(route('dashboard'));

    expect($response)->assertViewHas('days', function ($daysCount) {
        return $daysCount == Day::count();
    });
});

test('It shows the correct Users count on index page', function () {
    $expectedUsersCount = 3;

    createUsersForAssociation(AssociationStorage::current(), $expectedUsersCount);

    $response = get(route('dashboard'));

    expect($response)->assertViewHas('users', function ($usersCount) use ($expectedUsersCount) {
        return $usersCount == $expectedUsersCount;
    });
});

test('It shows the correct Tables count on index page', function () {
    createUsersForAssociation(AssociationStorage::current());

    $day = createDayForAssociation(AssociationStorage::current(), now());

    createTable($day, '13:00');
    createTable($day, '14:30');
    createTable($day, '15:00');

    $response = get(route('dashboard'));

    expect($response)->assertViewHas('tablesCount', function ($tablesCount) {
        return $tablesCount == 3;
    });
});
