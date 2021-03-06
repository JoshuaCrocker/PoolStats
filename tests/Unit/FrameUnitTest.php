<?php

namespace Tests\Unit;

use App\LeagueFrame;
use App\LeagueFramePlayer;
use App\LeagueMatch;
use App\Player;
use App\PlayerTeam;
use App\Team;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrameUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_generate_its_endpoint()
    {
        // Given we have a test
        $match = create(LeagueMatch::class);
        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        // it can generate its endpoint
        $this->assertEquals(
            $frame->endpoint(),
            '/matches/' . $match->id . '/frames/' . $frame->id
        );
    }

    /**
     * @test
     */
    public function it_can_retrieve_its_players()
    {
        // Given we have a frame
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        // which has two players
        $playerHome = create(Player::class);
        $playerAway = create(Player::class);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerHome->id
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerAway->id
        ]);

        // It can retrieve all players
        $this->assertEquals(2, $frame->players->count());

        $this->assertEquals($playerHome->name, $frame->players[0]->player->name);
        $this->assertEquals($playerAway->name, $frame->players[1]->player->name);
    }

    /**
     * @test
     */
    public function it_can_get_its_match()
    {
        // Given we have a frame
        $match = create(LeagueMatch::class);

        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match
        ]);

        // We can get the match details
        $this->assertEquals($match->leagueId, $frame->match->leagueId);
        $this->assertEquals($match->venueId, $frame->match->venueId);
        $this->assertEquals($match->matchDate, $frame->match->matchDate);
    }

    /**
     * @test
     */
    public function it_can_retrieve_the_home_player()
    {
        // Given we have a frame
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        // which has two players
        $playerHome = create(Player::class);
        $playerAway = create(Player::class);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamAway->id,
            'player_id' => $playerAway->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerHome->id
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerAway->id
        ]);

        // It can retrieve the home player
        $this->assertEquals($playerHome->name, $frame->homePlayer->name);
    }

    /**
     * @test
     */
    public function it_can_retrieve_the_away_player()
    {
        // Given we have a frame
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        // which has two players
        $playerHome = create(Player::class);
        $playerAway = create(Player::class);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamAway->id,
            'player_id' => $playerAway->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerHome->id
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerAway->id
        ]);

        // It can retrieve the away player
        $this->assertEquals($playerAway->name, $frame->awayPlayer->name);
    }

    /**
     * @test
     */
    public function it_can_tell_what_type_of_frame_it_is()
    {
        // Single Frame
        $single = create(LeagueFrame::class, [
            'doubles' => FALSE
        ]);

        $this->assertEquals('single', $single->type);

        // Double Frame
        $double = create(LeagueFrame::class, [
            'doubles' => TRUE
        ]);

        $this->assertEquals('double', $double->type);
    }

    /**
     * @test
     */
    public function it_can_retrieve_the_home_players()
    {
        // Given we have a frame
        // which has four players
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        $playerHome1 = $this->playerWithTeam($teamHome);
        $playerHome2 = $this->playerWithTeam($teamHome);
        $playerAway1 = $this->playerWithTeam($teamAway);
        $playerAway2 = $this->playerWithTeam($teamAway);

        $frame = $this->doublesFrameWithPlayers($match, $playerHome1['player'], $playerHome2['player'],
            $playerAway1['player'], $playerAway2['player'], 'away');

        // We can get the away players
        $this->assertContains($playerHome1['player']->name, $frame['frame']->homePlayers[0]->name);
        $this->assertContains($playerHome2['player']->name, $frame['frame']->homePlayers[1]->name);
    }

    /**
     * @test
     */
    public function it_can_retrieve_the_away_players()
    {
        // Given we have a frame
        // which has four players
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        $playerHome1 = $this->playerWithTeam($teamHome);
        $playerHome2 = $this->playerWithTeam($teamHome);
        $playerAway1 = $this->playerWithTeam($teamAway);
        $playerAway2 = $this->playerWithTeam($teamAway);

        $frame = $this->doublesFrameWithPlayers($match, $playerHome1['player'], $playerHome2['player'],
            $playerAway1['player'], $playerAway2['player'], 'away');

        // We can get the away players
        $this->assertContains($playerAway1['player']->name, $frame['frame']->awayPlayers[0]->name);
        $this->assertContains($playerAway2['player']->name, $frame['frame']->awayPlayers[1]->name);
    }

    /**
     * @test
     */
    public function it_can_determine_if_the_match_was_a_draw()
    {
        $match = create(LeagueMatch::class);

        $frames = $this->frameWithPlayers($match, null, null, 'draw');

        $this->assertTrue($frames['frame']->isDraw());
    }

    /**
     * @test
     */
    public function it_can_determine_if_the_doubles_match_was_a_draw()
    {
        $match = create(LeagueMatch::class);

        $frames = $this->doublesFrameWithPlayers($match, null, null, null, null, 'draw');

        $this->assertTrue($frames['frame']->isDraw());
    }

    /**
     * @test
     */
    public function it_doesnt_incorrectly_call_it_a_draw()
    {
        $match = create(LeagueMatch::class);

        $frames = $this->frameWithPlayers($match, null, null, 'home');

        $this->assertFalse($frames['frame']->isDraw());
    }
}
