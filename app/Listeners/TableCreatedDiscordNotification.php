<?php

namespace App\Listeners;

use App\Models\Game;
use GuzzleHttp\Client;
use App\Events\TableCreated;
use App\Services\DiscordService;
use Illuminate\Support\Facades\Auth;

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

        $embedMessage = DiscordService::buildEmbedNotificationMessage($event, 'create');

        DiscordService::sendNotification($discordChannelId, $embedMessage);
    }
}
