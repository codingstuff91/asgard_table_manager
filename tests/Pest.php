<?php

use App\Models\User;
use App\Notifications\Discord\Strategies\CreateMessageAndThread;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Mocks\CreateMessageAndThreadFake;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/
function login(?User $user = null): void
{
    actingAs($user ?? User::factory()->create());
}

function loginAdmin(?User $user = null): void
{
    actingAs($user ?? User::factory()->admin()->create());
}

function mockHttpClient(): void
{
    $guzzleMock = Mockery::mock(Client::class);
    $guzzleMock->shouldReceive('post');

    app()->instance(Client::class, $guzzleMock);
}

function mockCreateMessageAndThreadStrategy(): void
{
    $strategyFake = app(CreateMessageAndThreadFake::class);

    app()->instance(CreateMessageAndThread::class, $strategyFake);
}
