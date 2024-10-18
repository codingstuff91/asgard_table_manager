<?php

namespace App\Notifications\Discord\Table;

use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Notifications\Discord\DiscordNotification;
use App\Notifications\Discord\Strategies\CreateMessage;
use App\Notifications\Discord\Strategies\UpdateMessage;
use Illuminate\Support\Facades\Auth;

class UpdateTableNotification extends DiscordNotification
{
    public function buildMessage(): self
    {
        $this->message = [
            'content' => '@everyone '.EmbedMessageContent::UPDATED->value,
            'embeds' => [
                [
                    'author' => [
                        'name' => 'Organisateur : '.Auth::user()->name,
                    ],
                    'title' => 'Jeu proposé : '.$this->discordNotificationData->game->name,
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
                            'value' => $this->discordNotificationData->table->description ?? 'Aucune description fournie',
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
        $updateMessageStrategy = app(UpdateMessage::class);

        app(SendDiscordNotificationAction::class)(
            $updateMessageStrategy,
            $this->channelId,
            $this->message,
            $this->discordNotificationData->table
        );

        $messageCreationStrategy = app(CreateMessage::class);

        $this->createUpdateMessage();

        app(SendDiscordNotificationAction::class)(
            $messageCreationStrategy,
            $this->channelId,
            $this->message,
            $this->discordNotificationData->table
        );
    }

    private function createUpdateMessage(): void
    {
        $this->message = [
            'content' => EmbedMessageContent::UPDATED->value,
            'embeds' => [
                [
                    'title' => '⚠️ Table de '.$this->discordNotificationData->table->game->name.' mise à jour par '.auth::user()->name,
                    'color' => EmbedColor::WARNING->value,
                ],
            ],
        ];
    }
}
