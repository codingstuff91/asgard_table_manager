<?php

namespace App\Notifications\Discord;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\Strategies\CreateMessageAndThread;
use Illuminate\Support\Facades\Auth;

class CreateTableNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::CREATED->value,
            'embeds' => [
                [
                    'title' => 'Table de : '.$this->discordNotificationData->game->name,
                    'description' => self::setEmbedDescription($this->discordNotificationData),
                    'author' => [
                        'name' => 'Créateur : '.Auth::user()->name,
                    ],
                    'color' => EmbedColor::CREATED->value,
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $this->discordNotificationData->day->date->format('d/m/Y'),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Heure',
                            'value' => $this->discordNotificationData->table->start_hour,
                            'inline' => true,
                        ],
                    ],
                    'footer' => [
                        'text' => $this->discordNotificationData->table->description,
                    ],
                ],
            ],
        ];

        return $this;
    }

    public function send(): void
    {
        $messageCreationStrategy = app(CreateMessageAndThread::class);

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            $this->channelId,
            $this->message,
            $this->discordNotificationData->table
        );
    }

    private static function setEmbedDescription(DiscordNotificationData $discordNotificationData): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$discordNotificationData->day->id;
    }
}
