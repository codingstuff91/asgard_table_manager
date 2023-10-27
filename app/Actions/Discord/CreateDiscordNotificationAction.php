<?php

namespace App\Actions\Discord;

use App\DataObjects\DiscordNotificationData;

class CreateDiscordNotificationAction
{
    public function __construct(
        public DefineChannelIdAction $defineChannelIdAction,
        public BuildEmbedMessageStructureAction $buildEmbedMessageStructureAction,
        public SendDiscordNotificationAction $sendDiscordNotificationAction,
    ){
    }

    public function __invoke(
        DiscordNotificationData $discordNotificationData,
        string $notificationType,
    ): void
    {
        $channelId = ($this->defineChannelIdAction)($discordNotificationData->day->date);

        $embedMessage = $this->buildEmbedMessageStructureAction::buildEmbedStructure($discordNotificationData, $notificationType);

        ($this->sendDiscordNotificationAction)($channelId, $embedMessage);
    }
}
