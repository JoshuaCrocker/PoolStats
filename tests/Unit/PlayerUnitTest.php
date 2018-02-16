<?php

namespace Tests\Unit;

use App\Player;
use App\PlayerTeam;
use App\Team;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PlayerUnitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_can_get_details_about_its_current_team()
    {
        // Given we have a player
        $player = create(Player::class);

        // and they're a member of a team
        $team = create(Team::class);

        create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // we can get details about the team from the Player class
        $this->assertEquals($player->team->id, $team->id);
        $this->assertEquals($player->team->name, $team->name);
    }

    /**
     * @test
     */
    public function it_only_returns_the_current_team()
    {
        // Given we have a player
        $player = create(Player::class);

        // and they're a member of a team
        $team = create(Team::class);

        create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // and previously a member of another team
        $historicTeam = create(Team::class);

        create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $historicTeam->id,
            'member_from' => Carbon::parse('-7 days'),
            'member_to' => Carbon::parse('-2 days')
        ]);

        // we can get details about the current team from the Player class
        $this->assertEquals($player->team->id, $team->id);
        $this->assertEquals($player->team->name, $team->name);
    }

    /**
     * @test
     */
    public function it_doesnt_return_a_historic_membership()
    {
        // Given we have a player
        $player = create(Player::class);

        // and they were previously a member of a team
        $historicTeam = create(Team::class);

        create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $historicTeam->id,
            'member_from' => Carbon::parse('-7 days'),
            'member_to' => Carbon::parse('-2 days')
        ]);

        // we can get details about the current team from the Player class
        $this->assertNull($player->team);
    }

    /**
     * @test
     */
    public function it_returns_the_correct_membership_to_the_correct_players()
    {
        // Given we have two players
        $player1 = create(Player::class);
        $player2 = create(Player::class);

        // and two teams
        $team1 = create(Team::class);
        $team2 = create(Team::class);

        // with one player in each team
        create(PlayerTeam::class, [
            'player_id' => $player1->id,
            'team_id' => $team1->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'player_id' => $player2->id,
            'team_id' => $team2->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // the player class returns the correct membership
        $this->assertEquals($player1->team->id, $team1->id);
        $this->assertEquals($player1->team->name, $team1->name);

        $this->assertEquals($player2->team->id, $team2->id);
        $this->assertEquals($player2->team->name, $team2->name);
    }

    /**
     * @test
     */
    public function it_can_generate_its_endpoint()
    {
        // Given we have a Match
        $player = create(Player::class);

        // it can generate its endpoint
        $this->assertEquals(
            $player->endpoint(),
            '/players/1'
        );
    }
}
