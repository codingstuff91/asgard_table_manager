<?php

namespace App\Notifications\Discord\Table;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessageIntoThread;
use Illuminate\Support\Facades\Auth;

class UpdateTableNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => EmbedMessageContent::UPDATED->value,
            'embeds' => [
                [
                    'title' => 'Table de : '.$this->discordNotificationData->game->name,
                    'description' => self::setEmbedDescription($this->discordNotificationData->day->id),
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

    private static function setEmbedDescription(int $dayId): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$dayId;
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
