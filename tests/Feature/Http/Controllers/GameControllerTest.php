<?php

use function Pest\Laravel\get;

test('The create game page is rendered', function () {
    login();

    $response = get('games/create');

    expect($response)->toBeRedirect('/admin/games/create');
});
