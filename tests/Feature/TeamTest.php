<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function it_displays_a_list_of_teams()
    {
        // Given we have a team ...
        $team = create(\App\Team::class);

        // ... and we go to the teams page ...
        $response = $this->get('/teams');

        // ... we see the team in the list
        $response->assertSee($team->name);
    }
}
