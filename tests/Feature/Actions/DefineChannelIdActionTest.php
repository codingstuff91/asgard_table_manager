<?php

use App\Actions\Discord\DefineChannelIdAction;

test('The correct channel ID is returned according to the day of a date', function (string $date, int $discordChannel) {
    $channelId = app(DefineChannelIdAction::class)($date);

    expect($channelId)->toBe($discordChannel);
})->with([
    'Friday' => ['2023-05-19', 1069338626237931541],
    'Saturday' => ['2023-05-20', 1069338674753437850],
    'Sunday' => ['2023-05-21', 1069338721633194025],
    'Another Day' => ['2023-05-18', 1069369413570138192],
]);
