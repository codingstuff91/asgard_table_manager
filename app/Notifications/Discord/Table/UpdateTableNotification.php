<?php

namespace App\Notifications\Discord\Table;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessageIntoThread;

class UpdateTableNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::UPDATED->value,
            'embeds' => [
                [
                    'color' => EmbedColor::CREATED->value,
                    'fields' => [
                        [
                            'name' => 'Heure',
                            'value' => $this->discordNotificationData->table->start_hour,
                            'inline' => true,
                        ],
                        [
                            'name' => 'Description',
                            'value' => $this->discordNotificationData->table->description,
                            'inline' => false,
                        ],
                        [
                            'name' => 'Lien Inscription',
                            'value' => '[Cliquez ici]('.$this->setExternalLink($this->discordNotificationData).')',
                            'inline' => false,
                        ],
                    ],
                ],
            ],
        ];

        return $this;
    }

    private static function setExternalLink(DiscordNotificationData $discordNotificationData): string
    {
        return config('app.url').'/days/'.$discordNotificationData->day->id;
    }

    public function send(): void
    {
        $messageCreationStrategy = app(CreateMessageIntoThread::class);

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            $this->channelId,
            $this->message,
            $this->discordNotificationData->table
        );
    }
}
