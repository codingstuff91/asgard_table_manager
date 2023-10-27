<?php

namespace App\Actions\Discord;

use App\DataObjects\DiscordNotificationData;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageTitle;
use Illuminate\Support\Facades\Auth;

class BuildEmbedMessageStructureAction
{
    public static function buildEmbedStructure(DiscordNotificationData $discordNotificationData, string $notificationType): array
    {
        return match ($notificationType) {
            'create', 'update' => self::generateLongEmbed($discordNotificationData, $notificationType),
            'delete' => self::generateShortEmbed($discordNotificationData, $notificationType),
        };
    }

    public static function generateLongEmbed(
        DiscordNotificationData $discordNotificationData,
        string $notificationType
    ): array
    {
        return [
            'content' => self::setEmbedContent($notificationType),
            'embeds' => [
                [
                    'title' => 'Table de : ' . $discordNotificationData->game->name,
                    'description' => self::setEmbedTitle($discordNotificationData),
                    'author' => [
                        'name' => 'Créateur : ' . Auth::user()->name,
                    ],
                    'color' => EmbedColor::CREATED_OR_UPDATED,
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

    private static function generateShortEmbed(
        DiscordNotificationData $discordNotificationData,
        string $notificationType
    ): array
    {
        return [
            'content' => self::setEmbedContent($notificationType),
            'embeds' => [
                [
                    'title' => 'La Table de '.$discordNotificationData->game->name.' prévue le '.$discordNotificationData->day->date->format('d/m/Y').' à '.$discordNotificationData->table->start_hour.' est annulée.',
                    'color' => EmbedColor::DELETED,
                    'author' => [
                        'name' => 'Annulée par : '. Auth::user()->name,
                    ],
                ],
            ],
        ];
    }

    public static function setEmbedContent(string $notificationType): string
    {
        return match ($notificationType) {
            'create' => EmbedMessageTitle::CREATED->value,
            'update' => EmbedMessageTitle::UPDATED->value,
            'delete' => EmbedMessageTitle::DELETED->value,
        };
    }

    private static function setEmbedTitle(DiscordNotificationData $discordNotificationData): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$discordNotificationData->day->id;
    }
}
