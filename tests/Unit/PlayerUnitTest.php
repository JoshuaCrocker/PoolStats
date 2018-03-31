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
    public function it_can_get_details_about_its_current_team_membership()
    {
        // Given we have a player
        $player = create(Player::class);

        // and they're a member of a team
        $team = create(Team::class);

        $membership = create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // we can get details about the team from the Player class
        $this->assertEquals($player->membership->member_from, $membership->member_from);
        $this->assertEquals($player->membership->member_to, $membership->member_to);
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
        // and two teams
        // with one player in each team

        $team1 = create(Team::class);
        $team2 = create(Team::class);

        $player1 = $this->playerWithTeam($team1)['player'];
        $player2 = $this->playerWithTeam($team2)['player'];

        // the player class returns the correct membership
        $this->assertEquals($team1->id, $player1->team->id);
        $this->assertEquals($team1->name, $player1->team->name);

        $this->assertEquals($team2->id, $player2->team->id);
        $this->assertEquals($team2->name, $player2->team->name);
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

    /**
     * @test
     */
    public function it_can_get_all_memberships()
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

        // and they are a member of a team
        $currentTeam = create(Team::class);

        create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $currentTeam->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+7 days')
        ]);

        // The memberships are listed
        $this->assertCount(2, $player->memberships);
    }
}
