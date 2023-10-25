<?php

namespace App\Listeners;

use App\Events\TableCreated;
use App\Services\DiscordService;

class TableCreatedDiscordNotification
{
    /**
     * Send a discord notification according to day of week
     */
    public function handle(TableCreated $event): void
    {
        $discordChannelId = resolve(DiscordService::class)->getChannelByDate($event->day->date);

        $embedMessage = DiscordService::buildEmbedStructure($event, 'create');

        DiscordService::sendNotification($discordChannelId, $embedMessage);
    }
}
