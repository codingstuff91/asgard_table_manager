<?php

use App\Actions\Discord\BuildDayEmbedMessageStructureAction;
use App\Enums\EmbedColor;
use App\Enums\EmbedMessageContent;
use App\Models\Day;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $this->seed();
    login();
});

it('builds the embed message structure when cancelling a table', function () {
    $day = Day::first();
    $explanation = 'Explanation example';

    $embedMessageStructure = app(BuildDayEmbedMessageStructureAction::class)::buildEmbedStructure($day, $explanation, 'cancel');

    expect($embedMessageStructure['content'])->toBe(EmbedMessageContent::CANCELLED->value)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe('La session du '.$day->date->format('d/m/Y').' doit être annulée.')
        ->and($embedMessageStructure['embeds'][0]['description'])->toBe('Plus d\'informations sur '.route('days.show', $day->id))
        ->and($embedMessageStructure['embeds'][0]['author']['name'])->toBe('Annulée par : '.Auth::user()->name)
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::DELETED->value);
});

it('builds the embed message structure when puts a warning on a table', function () {
    $day = Day::first();
    $explanation = 'Explanation example';

    $embedMessageStructure = app(BuildDayEmbedMessageStructureAction::class)::buildEmbedStructure($day, $explanation, 'warning');

    expect($embedMessageStructure['content'])->toBe(EmbedMessageContent::WARNING->value)
        ->and($embedMessageStructure['embeds'][0]['title'])->toBe('La capacité en salles disponibles pour le '.$day->date->format('d/m/Y').' est réduite.')
        ->and($embedMessageStructure['embeds'][0]['description'])->toBe('Plus d\'informations sur '.route('days.show', $day->id))
        ->and($embedMessageStructure['embeds'][0]['author']['name'])->toBe('Annulée par : '.Auth::user()->name)
        ->and($embedMessageStructure['embeds'][0]['color'])->toBe(EmbedColor::WARNING->value);
});
