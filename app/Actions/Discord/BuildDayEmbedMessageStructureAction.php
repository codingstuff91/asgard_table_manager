<?php

namespace App\Actions\Discord;

use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Models\Day;
use Illuminate\Support\Facades\Auth;

class BuildDayEmbedMessageStructureAction
{
    public static function buildEmbedStructure(
        Day $day,
        string $explanation,
        string $notificationType
    ): array {
        return match ($notificationType) {
            'cancel' => self::generateDayCancelledStructure($day, $notificationType, $explanation),
            'warning' => self::generateDayWarningStructure($day, $notificationType, $explanation),
        };
    }

    private static function generateDayCancelledStructure(Day $day, string $notificationType, string $explanation): array
    {
        return [
            'content' => self::setEmbedContent($notificationType),
            'embeds' => [
                [
                    'title' => 'La session du '.$day->date->format('d/m/Y').' doit être annulée.',
                    'description' => self::setEmbedDescription($day),
                    'color' => self::setEmbedColor($notificationType),
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
                    ],
                    'footer' => [
                        'text' => $explanation,
                    ],
                ],
            ],
        ];
    }

    private static function generateDayWarningStructure(Day $day, string $notificationType, string $explanation): array
    {
        return [
            'content' => self::setEmbedContent($notificationType),
            'embeds' => [
                [
                    'title' => 'La capacité en salles disponibles pour le '.$day->date->format('d/m/Y').' est réduite.',
                    'description' => self::setEmbedDescription($day),
                    'color' => self::setEmbedColor($notificationType),
                    'author' => [
                        'name' => 'Annulée par : '.Auth::user()->name,
                    ],
                    'footer' => [
                        'text' => $explanation,
                    ],
                ],
            ],
        ];
    }

    public static function setEmbedContent(string $notificationType): string
    {
        return match ($notificationType) {
            'cancel' => EmbedMessageContent::CANCELLED->value,
            'warning' => EmbedMessageContent::WARNING->value,
        };
    }

    private static function setEmbedColor(string $notificationType): int
    {
        return match ($notificationType) {
            'cancel' => EmbedColor::DELETED->value,
            'warning' => EmbedColor::WARNING->value,
        };
    }

    private static function setEmbedDescription(Day $day): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$day->id;
    }
}
