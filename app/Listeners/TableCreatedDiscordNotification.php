<?php

namespace App\Listeners;

use App\Events\TableCreated;
use App\Services\DiscordService;

class TableCreatedDiscordNotification
{
    /**
     * Send a discord notification according to day of week
     *
     * @return void
     */
    public function handle(TableCreated $event)
    {
        $discordChannelId = resolve(DiscordService::class)->getChannelByDate($event->day->date);

        $embedMessage = DiscordService::buildEmbedStructure($event, 'create');

        DiscordService::sendNotification($discordChannelId, $embedMessage);
    }
}
