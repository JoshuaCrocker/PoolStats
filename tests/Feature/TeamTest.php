<?php

namespace Tests\Feature;

use App\PlayerTeam;
use App\Team;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_displays_a_list_of_teams()
    {
        $team = create(Team::class);

        $response = $this->get('/teams');

        $response->assertSee($team->name);
    }

    /**
     * @todo Ensure the user is logged in
     * @test
     */
    public function a_new_team_can_be_created()
    {
        $team = make(Team::class)->toArray();
        $this->post('/teams', $team);

        $this->assertDatabaseHas('teams', $team);
    }

    /**
     * @test
     */
    public function a_new_team_must_have_a_name()
    {
        $this->withExceptionHandling();

        $team = make(
            Team::class,
            ['name' => '']
        )->toArray();

        $response = $this->post('/teams', $team);

        $response->assertSessionHasErrors('name');
    }

    /**
     * @test
     */
    public function a_team_can_be_updated()
    {
        // Given we have a team
        $team = create(Team::class);

        // and we update the name
        $update_data = [
            'name' => 'Updated Team'
        ];

        $this->patch($team->endpoint(), $update_data);

        // the name changes in the database
        $this->assertDatabaseHas('teams', $update_data);
    }

    /**
     * @test
     */
    public function a_team_can_be_deleted()
    {
        // When we have a team ...
        $team = create(Team::class);

        // ... and we hit the delete endpoint ...
        $this->delete($team->endpoint());

        // ... the team is (soft) deleted
        $this->assertSoftDeleted('teams', $team->toArray());
    }

    /**
     * @test
     */
    public function a_team_displays_its_details_at_its_endpoint()
    {
        // Given we have a team ...
        $team = create(Team::class);

        // ... and we GET its endpoint ...
        $response = $this->get($team->endpoint());

        // .. the team should be displayed
        $response->assertSee($team->name);
    }

    /**
     * @test
     */
    public function a_team_displays_its_members_on_its_page()
    {
        // Given we have a team ...
        // ... and that team has a member ...
        $player_team = create(PlayerTeam::class);

        // ... the member is displayed on the team page ...
        $response = $this->get($player_team->team->endpoint());

        $response->assertSee($player_team->player->name);
    }

}
