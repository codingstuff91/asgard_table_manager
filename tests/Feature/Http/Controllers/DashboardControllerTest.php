<?php

use App\Models\Association;
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
