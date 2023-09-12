<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Concerns\WithoutRefreshDatabase;

class PlaygroundRouteTest extends TestCase
{
    use WithoutRefreshDatabase;

    /** @test */
    public function it_has_playground_routes()
    {
        $this->json('get', '/playground')
            ->assertOk()
            ->assertSee('Welcome to your playground!');
    }
}
