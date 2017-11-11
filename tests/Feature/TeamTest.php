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

    /** @test */
    public function a_new_team_can_be_created()
    {
        // When we post a new team
        $team = make(\App\Team::class)->toArray();
        $this->post('/teams', $team);

        // It appears in the database
        $this->assertDatabaseHas('teams', $team);
    }


}
