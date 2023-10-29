<?php

namespace App\Actions\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use Illuminate\Support\Facades\Auth;

class BuildEmbedMessageStructureAction
{
    public static function buildEmbedStructure(DiscordNotificationData $discordNotificationData, string $notificationType): array
    {
        return match ($notificationType) {
            'create', 'update' => self::generateLongTableStructure($discordNotificationData, $notificationType),
            'delete' => self::generateShortTableStructure($discordNotificationData, $notificationType),
            'subscribe', 'unsubscribe' => self::generateSubscribingStructure($discordNotificationData, $notificationType),
        };
    }

    public static function generateLongTableStructure(
        DiscordNotificationData $discordNotificationData,
        string $notificationType
    ): array {
        return [
            'content' => self::setEmbedContent($notificationType),
            'embeds' => [
                [
                    'title' => 'Table de : '.$discordNotificationData->game->name,
                    'description' => self::setEmbedDescription($discordNotificationData),
                    'author' => [
                        'name' => 'Créateur : '.Auth::user()->name,
                    ],
                    'color' => self::setEmbedColor($notificationType),
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $discordNotificationData->day->date->format('d/m/Y'),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Heure',
                            'value' => $discordNotificationData->table->start_hour,
                            'inline' => true,
                        ],
                    ],
                    'footer' => [
                        'text' => $discordNotificationData->table->description,
                    ],
                ],
            ],
        ];
    }

    private static function generateShortTableStructure(
        DiscordNotificationData $discordNotificationData,
        string $notificationType
    ): array {
        return [
            'content' => self::setEmbedContent($notificationType),
            'embeds' => [
                [
                    'title' => 'La Table de '.$discordNotificationData->game->name.' prévue le '.$discordNotificationData->day->date->format('d/m/Y').' à '.$discordNotificationData->table->start_hour.' est annulée.',
                    'color' => self::setEmbedColor($notificationType),
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
                    ],
                ],
            ],
        ];
    }

    public static function generateSubscribingStructure(
        DiscordNotificationData $discordNotificationData,
        string $notificationType
    ): array
    {
        $titleKeyword = $notificationType == 'subscribe' ? 'inscrit à' : 'désinscrit de';

        return [
            'content' => self::setEmbedContent($notificationType),
            'embeds' => [
                [
                    'title' => Auth::user()->name.' s\'est '.$titleKeyword.' la table de '.$discordNotificationData->game->name,
                    'description' => self::setEmbedDescription($discordNotificationData),
                    'color' => self::setEmbedColor($notificationType),
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $discordNotificationData->day->date->format('d/m/Y'),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Heure',
                            'value' => $discordNotificationData->table->start_hour,
                            'inline' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function setEmbedContent(string $notificationType): string
    {
        return match ($notificationType) {
            'create' => EmbedMessageContent::CREATED->value,
            'update' => EmbedMessageContent::UPDATED->value,
            'delete' => EmbedMessageContent::DELETED->value,
            'subscribe' => EmbedMessageContent::SUBSCRIBED->value,
            'unsubscribe' => EmbedMessageContent::UNSUBSCRIBED->value,
        };
    }

    private static function setEmbedColor(string $notificationType): int
    {
        return match ($notificationType) {
            'create', 'update', 'subscribe' => EmbedColor::CREATED->value,
            'delete', 'unsubscribe' => EmbedColor::DELETED->value,
        };
    }

    private static function setEmbedDescription(DiscordNotificationData $discordNotificationData): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$discordNotificationData->day->id;
    }
}
