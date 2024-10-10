<?php

namespace App\Notifications\Discord\Table;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
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
                    'title' => 'Jeu proposé : '.$this->discordNotificationData->game->name,
                    'description' => $this->setEmbedDescription($this->discordNotificationData),
                    'author' => [
                        'name' => 'Organisateur : '.Auth::user()->name,
                    ],
                    'color' => EmbedColor::CREATED->value,
                    'fields' => [
                        [
                            'name' => 'Catégorie',
                            'value' => $this->discordNotificationData->game->category->name,
                            'inline' => false,
                        ],
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
                        [
                            'name' => 'Description',
                            'value' => $this->discordNotificationData->table->description,
                            'inline' => false,
                        ],
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
        return 'Pour s\'inscrire, cliquez ici : '.config('app.url').'/days/'.$discordNotificationData->day->id;
    }

    private function setObjectMessage(string $gameCategory): string
    {
        return "Nouvelle table de $gameCategory";
    }
}
