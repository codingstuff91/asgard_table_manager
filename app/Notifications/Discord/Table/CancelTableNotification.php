<?php

namespace App\Notifications\Discord\Table;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessage;
use App\Services\DiscordService;
use Illuminate\Support\Facades\Auth;

class CancelTableNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::DELETED->value,
            'embeds' => [
                [
                    'title' => self::setMessageTitle($this->discordNotificationData),
                    'color' => EmbedColor::DELETED->value,
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
                    ],
                ],
            ],
        ];

        return $this;
    }

    private static function setMessageTitle(DiscordNotificationData $discordNotificationData): string
    {
        return 'La Table de '.$discordNotificationData->gameName().' prévue le '.$discordNotificationData->getDay().' à '.$discordNotificationData->getStartHour().' est annulée.';
    }

    public function send(): void
    {
        // Original created message deletion
        app(DiscordService::class)->deleteMessage($this->channelId, $this->discordNotificationData->getMessageId());

        $messageCreationStrategy = app(CreateMessage::class);

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            $this->channelId,
            $this->message,
            $this->discordNotificationData->table
        );
    }
}
