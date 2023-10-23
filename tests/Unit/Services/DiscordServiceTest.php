<?php

use App\Enums\EmbedMessageTitle;
use App\Services\DiscordService;

test('The correct channel ID is returned according to the day of a date', function (string $date, int $discordChannel) {
    $discordService = new DiscordService;

    $channelId = $discordService->getChannelByDate($date);

    expect($channelId)->toBe($discordChannel);
})->with([
    'Friday' => ['2023-05-19', 1069338626237931541],
    'Saturday' => ['2023-05-20', 1069338674753437850],
    'Sunday' => ['2023-05-21', 1069338721633194025],
    'Another Day' => ['2023-05-18', 1069369413570138192],
]);

test('the notification content text is set correctly', function (string $eventType, EmbedMessageTitle $embedMessageTitle) {
    $discordService = new DiscordService;

    $contentText = $discordService::setNotificationContent($eventType);

    expect($contentText)->toBe($embedMessageTitle->value);
})->with([
    'Created event type' => ['create', EmbedMessageTitle::CREATED],
    'Updated event type' => ['update', EmbedMessageTitle::UPDATED],
    'Deleted event type' => ['delete', EmbedMessageTitle::DELETED],
]);
