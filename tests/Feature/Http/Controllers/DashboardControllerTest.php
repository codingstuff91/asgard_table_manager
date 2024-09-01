<?php

use function Pest\Laravel\get;

beforeEach(function () {
    login();
});

test('It renders the index page', function () {
    $response = get(route('dashboard'));

    expect($response)->toBeOk();
});
