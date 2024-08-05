<?php

namespace App\Actions\Discord;

use App\Models\Day;

class CreateDayDiscordNotificationAction
{
    public function __construct(
        public DefineChannelIdAction $defineChannelIdAction,
        public BuildDayEmbedMessageStructureAction $buildDayEmbedMessageStructureAction,
        public SendDiscordNotificationAction $sendDiscordNotificationAction,
    ) {
    }

    public function __invoke(
        Day $day,
        string $explanation,
        string $notificationType,
    ): void {
        $channelId = ($this->defineChannelIdAction)($day->date);

        $embedMessage = $this->buildDayEmbedMessageStructureAction::buildEmbedStructure($day, $explanation, $notificationType);

        ($this->sendDiscordNotificationAction)($channelId, $embedMessage);
    }
}
