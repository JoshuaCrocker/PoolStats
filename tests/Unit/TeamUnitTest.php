<?php

namespace Tests\Unit;

use App\Player;
use App\PlayerTeam;
use App\Team;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TeamUnitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_can_generate_its_endpoint()
    {
        // Given we have a test
        $team = create(Team::class);

        // it can generate its endpoint
        $this->assertEquals(
            $team->endpoint(),
            '/teams/1'
        );
    }

    /**
     * @test
     */
    public function it_can_list_its_current_roster()
    {
        $team = create(Team::class);
        $player1 = create(Player::class);
        $player2 = create(Player::class);

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player1->id
        ]);

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player2->id
        ]);

        $roster = $team->getCurrentRoster();

        $this->assertCount(2, $roster);
    }

    /**
     * @test
     */
    public function it_doesnt_include_members_who_have_left()
    {
        $team = create(Team::class);
        $player1 = create(Player::class);
        $player2 = create(Player::class);

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player1->id
        ]);

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player2->id,
            'member_from' => date('Y-m-d', strtotime('-1 month')),
            'member_to' => date('Y-m-d', strtotime('-2 weeks'))
        ]);

        $roster = $team->getCurrentRoster();

        $this->assertCount(1, $roster);
    }
}
