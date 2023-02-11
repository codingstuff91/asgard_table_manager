<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Day;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DayController
 */
class DayControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $days = Day::factory()->count(3)->create();

        $response = $this->get(route('day.index'));

        $response->assertOk();
        $response->assertViewIs('post.index');
        $response->assertViewHas('posts');
    }


    /**
     * @test
     */
    public function create_displays_view()
    {
        $response = $this->get(route('day.create'));

        $response->assertOk();
        $response->assertViewIs('day.create');
    }


    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $response = $this->post(route('day.store'));

        $response->assertRedirect(route('day.index'));

        $this->assertDatabaseHas(days, [ /* ... */ ]);
    }
}
