<?php

use App\Services\DiscordService;

use function Pest\Laravel\delete;

test('Call discord service method to delete main message after a table cancelation', function () {
    login();
    mockHttpClient();

    $table = createTable();

    $service = Mockery::mock(DiscordService::class);
    $service->shouldReceive('deleteMessage');

    $response = delete(route('table.delete', $table));

    expect($response->status())->toBe(302);
});
