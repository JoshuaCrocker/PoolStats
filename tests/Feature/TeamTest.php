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
        $team = create(\App\Team::class);

        $response = $this->get('/teams');

        $response->assertSee($team->name);
    }

    /** @test */
    public function a_new_team_can_be_created()
    {
        $team = make(\App\Team::class)->toArray();
        $this->post('/teams', $team);

        $this->assertDatabaseHas('teams', $team);
    }

    /** @test */
    public function a_new_team_must_have_a_name()
    {
        $this->withExceptionHandling();

        $team = make(
            \App\Team::class,
            ['name' => '']
        )->toArray();

        $response = $this->post('/teams', $team);

        $response->assertSessionHasErrors('name');
    }

}
