<?php

namespace Tests\Unit;

use App\LeagueFrame;
use App\LeagueFramePlayer;
use App\LeagueMatch;
use App\Player;
use App\PlayerTeam;
use App\Team;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
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

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player1->id,
            'member_from' => Carbon::parse('-1 week'),
            'member_to' => Carbon::parse('-1 day')
        ]);


        $roster = $team->getCurrentRoster();

        $this->assertCount(2, $roster);
    }

    /**
     * @test
     */
    public function it_can_list_its_historic_roster()
    {
        $team = create(Team::class);
        $player1 = create(Player::class);
        $player2 = create(Player::class);

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player1->id,
            'member_from' => Carbon::parse('+1 week'),
            'member_to' => Carbon::parse('+2 weeks')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player1->id,
            'member_from' => Carbon::parse('-1 week'),
            'member_to' => Carbon::parse('-1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $team->id,
            'player_id' => $player2->id,
            'member_from' => Carbon::parse('-1 week'),
            'member_to' => Carbon::parse('-1 day')
        ]);

        $roster = $team->getHistoricRoster();

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

    /**
     * @test
     */
    public function it_can_get_the_highest_performing_player()
    {
        // Given we have a team
        $team = create(Team::class);

        // with a few players
        $player1 = $this->playerWithTeam($team)['player'];
        $player2 = $this->playerWithTeam($team)['player'];

        // who have attended / played various frames
        $match = create(LeagueMatch::class, [
            'match_date' => Carbon::now(),
            'home_team_id' => $team->id
        ]);

        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        //- Player 1 Games
        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player1->id,
            'winner' => true
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player1->id,
            'winner' => true
        ]);

        //- Player 2 Games
        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player2->id,
            'winner' => true
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player2->id,
            'winner' => false
        ]);

        // Calculate the Highest Performing Player
        Artisan::call('stats:hpp');

        $this->assertEquals($player1->id, $team->highestPerformingPlayer->id);
        $this->assertEquals($player1->name, $team->highestPerformingPlayer->name);
    }
}
