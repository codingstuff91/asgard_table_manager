<?php

use App\Actions\Discord\BuildDayEmbedMessageStructureAction;
use App\Actions\Discord\CreateDayDiscordNotificationAction;
use App\Actions\Discord\DefineChannelIdAction;
use App\Actions\Discord\SendDiscordNotificationAction;
use App\Models\Day;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->seed();
    actingAs(User::first());
});

test('The index page is rendered correctly', function () {
    $response = $this->get(route('days.index'));

    expect($response)->toBeOk();
});

test('The create page is rendered correctly', function () {
    $response = $this->get(route('days.create'));

    expect($response)->toBeOk();
});

test('The show page is rendered correctly', function () {
    $response = $this->get(route('days.show', Day::first()->id));

    expect($response)->toBeOk();
});

test('A day can not be created twice', function () {
    $response = $this->post(route('days.store'), [
        'date' => now(),
    ]);

    expect($response)->toHaveInvalid(['date']);
});

test('A day can not be created without choosing a date', function () {
    $response = $this->post(route('days.store'), [
        'date' => '',
    ]);

    expect($response)->toHaveInvalid(['date']);
});

test('A day is created successfully', function () {
    $date = now()->add('day', 1);

    $response = $this->post(route('days.store'), [
        'date' => $date,
    ]);

    expect($response)->toHaveValid(['date'])
        ->and($response)->toBeRedirect(route('days.index'))
        ->and([
            'date' => $date,
        ])->toBeInDatabase(table: 'days');
});

test('The past days are hidden from index page', function () {
    $pastDay = Day::factory()->create([
        'date' => now()->sub('day', 1),
    ]);

    $response = $this->get(route('days.index'));

    expect($response)
        ->toBeOk()
        ->not->toContainText($pastDay->date->format('d/m/Y'));
});

test('the edit warning page is rendered correctly', function () {
    $day = Day::first();

    $response = get(route('days.warning', $day));

    expect($response)->toBeOk();
});

test('the warning could not be store without an explanation', function () {
    $day = Day::first();

    $response = $this->patch(route('days.confirm_warning', $day), [
        'explanation' => '',
    ]);

    expect($response)->toHaveInvalid(['explanation']);
});

test('A warning message can be stored', function () {
    $day = Day::first();
    $explanationTest = 'Example of explanation';

    $response = $this->patch(route('days.confirm_warning', $day), [
        'explanation' => $explanationTest,
    ]);

    expect($day->refresh()->explanation)->toBe($explanationTest)
        ->and($response)->toBeRedirect(route('days.index'));
});

test('The warning message is visible on the show page if exists', function () {
    $day = Day::first();
    $explanationTest = 'Example of explanation';

    $this->patch(route('days.confirm_warning', $day), [
        'explanation' => $explanationTest,
    ]);

    $showDayView = get(route('days.show', $day));

    expect($showDayView)->toContainText($explanationTest);
});

test('The warning message is hidden when it doesnt exists', function () {
    $day = Day::first();
    $explanationTest = 'Example of explanation';

    $showDayView = get(route('days.show', $day));

    expect($showDayView)->not()->toContainText($explanationTest)
        ->and($showDayView)->not()->toContainText("<h3 class='my-4 text-white text-center w-full bg-red-500 rounded-lg'>");
});

test('the cancel page is rendered correctly', function () {
    $day = Day::first();
    $showCancelDayView = get(route('days.cancel', $day));

    expect($showCancelDayView)->toBeOk();
});

test('the table cancelation could not be executed without an explanation', function () {
    $day = Day::first();

    $response = $this->patch(route('days.confirm_cancel', $day), [
        'explanation' => '',
    ]);

    expect($response)->toHaveInvalid(['explanation']);
});

test('The cancelation message is stored', function () {
    $day = Day::first();
    $cancellationMessage = 'Example of explanation';

    $this->patch(route('days.confirm_cancel', $day), [
        'explanation' => $cancellationMessage,
    ]);

    expect($day->refresh())
        ->explanation
        ->toBe($cancellationMessage);
});

test('The cancelation of a day must block the ability to create table', function () {
    $day = Day::first();
    $cancellationMessage = 'Example of explanation';

    $this->patch(route('days.confirm_cancel', $day), [
        'explanation' => $cancellationMessage,
    ]);

    expect($day->refresh())
        ->can_create_table
        ->toBe(0);
});

test('The cancellation of a day must delete every tables created', function () {
    $day = Day::first();

    $this->patch(route('days.confirm_cancel', $day), [
        'explanation' => 'Example of explanation',
    ]);

    expect($day->tables->count())->toBe(0);
});

test('The cancellation message is visible on the show page if exists', function () {
    $day = Day::first();
    $explanationTest = 'Example of explanation';

    $this->patch(route('days.confirm_cancel', $day), [
        'explanation' => $explanationTest,
    ]);

    $showDayView = get(route('days.show', $day));

    expect($showDayView)->toContainText($explanationTest);
});

test('The cancel discord notification is sent', function () {
    mockHttpClient();

    $discordNotificationAction = Mockery::mock(CreateDayDiscordNotificationAction::class);
    $discordNotificationAction->shouldReceive('__invoke');

    $day = Day::first();
    $explanationTest = 'Example of explanation';

    $this->patch(route('days.confirm_cancel', $day), [
        'explanation' => $explanationTest,
    ]);

    $channelId = app(DefineChannelIdAction::class)($day->date);

    $embedMessage = app(BuildDayEmbedMessageStructureAction::class)::buildEmbedStructure($day, $explanationTest, 'cancel');

    $response = app(SendDiscordNotificationAction::class)($channelId, $embedMessage);

    expect($response)->toBe('Discord notification sent');
});

test('The action buttons are visible for admin users on days index page', function () {
    loginAdmin();

    $indexPageResponse = get(route('days.index'));

    $indexPageResponse->assertSee('img/cancel.png');
    $indexPageResponse->assertSee('img/warning.png');
});

test('The action buttons are hidden for non admin users on days index page', function () {
    $user = User::firstWhere('admin', false);

    actingAs($user)
        ->get(route('days.index'))
        ->assertDontSee('img/cancel.png')
        ->assertDontSee('img/warning.png');
});

test('The create buttons are hidden for a cancelled day', function () {
    $day = Day::first();
    $user = User::first();

    $explanationTest = 'Example of explanation';

    $this->patch(route('days.confirm_cancel', $day), [
        'explanation' => $explanationTest,
    ]);

    actingAs($user)
        ->get(route('days.show', $day))
        ->assertDontSee('img/game-table.png')
        ->assertDontSee('img/calendar.png');
});
